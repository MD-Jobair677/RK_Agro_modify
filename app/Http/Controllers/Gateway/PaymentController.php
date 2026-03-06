<?php

namespace App\Http\Controllers\Gateway;

use App\Models\User;
use App\Models\Deposit;
use App\Lib\FormProcessor;
use App\Models\Transaction;
use App\Constants\ManageStatus;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->active();
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Deposit Methods';
        return view($this->activeTheme . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request){

        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        $user = auth()->user();
        $gatewayData = GatewayCurrency::whereHas('method', function ($gateway) {
                        $gateway->active();
                    })->where('method_code', request('gateway'))
                    ->where('currency', request('currency'))
                    ->first();

        if (!$gatewayData) {
            $toast[] = ['error', 'Invalid gateway'];
            return back()->withToasts($toast);
        }

        $amount = request('amount');
        if ($gatewayData->min_amount > $amount || $gatewayData->max_amount < $amount) {
            $toast[] = ['error', 'Please follow donation limit'];
            return back()->withToasts($toast);
        }

        $charge       = $gatewayData->fixed_charge + (($amount * $gatewayData->percent_charge) / 100);
        $payable      = $amount + $charge;
        $final_amount = $payable * $gatewayData->rate;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->method_code = $gatewayData->method_code;
        $data->method_currency = strtoupper($gatewayData->currency);
        $data->amount = $amount;
        $data->charge = $charge;
        $data->rate = $gatewayData->rate;
        $data->final_amount = $final_amount;
        $data->btc_amount = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->payment_try = 0;
        $data->status = 0;
        $data->save();
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    function depositConfirm() {
        $track   = session()->get('Track');
        $deposit = Deposit::with('gateway')->where('trx', $track)->initiated()->firstOrFail();
      
        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data    = $new::process($deposit);
        $data    = json_decode($data);

        if (isset($data->error)) {

            $toast[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withToasts($toast);
        }

        if (isset($data->redirect)) return redirect($data->redirect_url);

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';

        return view($this->activeTheme . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    static function userDataUpdate($deposit, $isManual = null) {
        if ($deposit->status == ManageStatus::PAYMENT_INITIATE || $deposit->status == ManageStatus::PAYMENT_PENDING) {
            $deposit->status = ManageStatus::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);
            $user->balance += $deposit->amount;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $deposit->user_id;
            $transaction->amount = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = $deposit->charge;
            $transaction->trx_type = '+';
            $transaction->details = 'Deposit Via ' . $deposit->gatewayCurrency()->name;
            $transaction->trx = $deposit->trx;
            $transaction->remark = 'deposit';
            $transaction->save();
        


            if (!$isManual) {
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = $user->id;
                $adminNotification->title = 'Deposit successful via '.$deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name' => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount' => showAmount($deposit->final_amount),
                'amount' => showAmount($deposit->amount),
                'charge' => showAmount($deposit->charge),
                'rate' => showAmount($deposit->rate),
                'trx' => $deposit->trx,
                'post_balance' => showAmount($user->balance)
            ]);
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Deposit Confirm';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view($this->activeTheme . 'user.payment.manual', compact('data', 'pageTitle', 'method','gateway'));
        }
        abort(404);
    }

    function manualDepositUpdate() {
        $track   = session()->get('Track');
        $deposit = Deposit::with('gateway')->where('trx', $track)->initiated()->first();

        if (!$deposit) return to_route(gatewayRedirectUrl());

        $gatewayCurrency = $deposit->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        request()->validate($validationRule);
        $userData = $formProcessor->processFormData(request(), $formData);

        $deposit->details = $userData;
        $deposit->status  = ManageStatus::PAYMENT_PENDING;
        $deposit->save();

        $adminNotification  = new AdminNotification();
        $adminNotification->user_id = $deposit->user->id;
        $adminNotification->title     = 'Deposit request from '.$deposit->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details',$deposit->id);
        $adminNotification->save();

        notify($deposit->user, 'DEPOSIT_REQUEST', [
            'method_name' => $deposit->gatewayCurrency()->name,
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amount),
            'amount' => showAmount($deposit->amount),
            'charge' => showAmount($deposit->charge),
            'rate' => showAmount($deposit->rate),
            'trx' => $deposit->trx
        ]);

        $toast[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withToasts($toast);

    }
}
