<?php

namespace App\Http\Controllers\User;

use App\Lib\FormProcessor;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\WithdrawMethod;
use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{


    public function withdrawMoney()
    {
        $withdrawMethod = WithdrawMethod::where('status',1)->get();
        $pageTitle = 'Withdraw Money';
        return view($this->activeTheme.'user.withdraw.methods', compact('pageTitle','withdrawMethod'));
    }


    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();

        $user = auth()->user();
        if ($request->amount < $method->min_amount) {
            $toast[] = ['error', 'Requested amount cannot be less than the minimum amount'];
            return back()->withToasts($toast);
        }
        if ($request->amount > $method->max_amount) {
            $toast[] = ['error', 'Requested amount cannot be greater than the maximum amount'];
             return back()->withToasts($toast);
        }

        if ($request->amount > $user->balance) {
            $toast[] = ['error', 'You don\'t have enough amount to make this withdrawal'];
             return back()->withToasts($toast);
        }

        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return to_route('user.withdraw.preview');
    }


    public function withdrawPreview()
    {
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id','desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';
        return view($this->activeTheme . 'user.withdraw.preview', compact('pageTitle','withdraw'));
    }

    public function withdrawSubmit(Request $request)
    {
        $withdraw = Withdrawal::with('method','user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id','desc')->firstOrFail();

        $method = $withdraw->method;
        if ($method->status == ManageStatus::INACTIVE) abort(404);
        $formData = $method->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $user = auth()->user();
        if ($user->ts) {
            $response = verifyG2fa($user,$request->authenticator_code);
            if (!$response) {
                $toast[] = ['error', 'Wrong verification code'];
                return back()->withToasts($toast);
            }
        }

        if ($withdraw->amount > $user->balance) {
            $toast[] = ['error', 'Your requested amount exceeds the current balance'];
            return back()->withToasts($toast);
        }

        $withdraw->status = ManageStatus::PAYMENT_PENDING;
        $withdraw->withdraw_information = $userData;
        $withdraw->save();
        
        $user->balance  -=  $withdraw->amount;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw Via ' . $withdraw->method->name;
        $transaction->trx = $withdraw->trx;
        $transaction->remark = 'withdraw';
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New withdraw request from '.$user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.pending');
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount),
            'amount' => showAmount($withdraw->amount),
            'charge' => showAmount($withdraw->charge),
            'rate' => showAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance),
        ]);

        $toast[] = ['success', 'Withdraw request sent successfully'];
        return to_route('user.withdraw.history')->withToasts($toast);
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdraw Log";
        $withdraws = Withdrawal::where('user_id', auth()->id())
        ->with('method')
        ->index()
        ->searchable(['trx'])
        ->latest()
        ->paginate(getPaginate());
        return view($this->activeTheme.'user.withdraw.index', compact('pageTitle','withdraws'));
    }

}
