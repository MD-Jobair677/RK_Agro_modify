<?php

namespace App\Http\Controllers\Admin;

use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{
    function index() {
        $pageTitle      = 'All Withdrawals';
        $withdrawalData = $this->withdrawalData(null, true);
        $withdrawals    = $withdrawalData['data'];
        $summary        = $withdrawalData['summary'];
        $done           = $summary['approved'];
        $pending        = $summary['pending'];
        $canceled       = $summary['rejected'];

        return view('admin.withdrawal.index', compact('pageTitle', 'withdrawals', 'done', 'pending', 'canceled'));
    }

    function pending() {
        $pageTitle   = 'Pending Withdrawals';
        $withdrawals = $this->withdrawalData('pending');

        return view('admin.withdrawal.index', compact('pageTitle', 'withdrawals'));
    }

    function approved() {
        $pageTitle   = 'Approved Withdrawals';
        $withdrawals = $this->withdrawalData('approved');

        return view('admin.withdrawal.index', compact('pageTitle', 'withdrawals'));
    }

    function Rejected() {
        $pageTitle   = 'Rejected Withdrawals';
        $withdrawals = $this->withdrawalData('rejected');

        return view('admin.withdrawal.index', compact('pageTitle', 'withdrawals'));
    }

    protected function withdrawalData($scope = null, $summary = false) {
        if ($scope) {
            $withdrawals = Withdrawal::with(['user', 'method'])->$scope();
        } else {
            $withdrawals = Withdrawal::with(['user', 'method'])->index();
        }

        $withdrawals = $withdrawals->searchable(['trx', 'user:username'])->dateFilter();

        // Filter By Payment Method
        if (request('method')) {
            $withdrawals = $withdrawals->where('method_id', request('method'));
        }

        if (!$summary) {
            return $withdrawals->latest()->paginate(getPaginate());
        } else {
            $doneSummary     = (clone $withdrawals)->approved()->sum('amount');
            $pendingSummary  = (clone $withdrawals)->pending()->sum('amount');
            $canceledSummary = (clone $withdrawals)->rejected()->sum('amount');

            return [
                'data'    => $withdrawals->latest()->paginate(getPaginate()),
                'summary' => [
                    'approved'     => $doneSummary,
                    'pending'  => $pendingSummary,
                    'rejected' => $canceledSummary,
                ],
            ];
        }
    }

    function approve() {
        $this->validate(request(), [
            'id' => 'required|int|gt:0'
        ]);

        $withdraw                 = Withdrawal::with('user')->where('id', request('id'))->pending()->firstOrFail();
        $withdraw->status         = ManageStatus::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = request('admin_feedback');
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'admin_details'   => request('admin_feedback'),
            'post_balance'    => showAmount($withdraw->user->balance),
        ]);

        $toast[] = ['success', 'Withdrawal approval success'];

        return back()->withToasts($toast);
    }

    function cancel() {
        $this->validate(request(), [
            'id'             => 'required|int|gt:0',
            'admin_feedback' => 'required|max:255',
        ]);

        $withdraw                 = Withdrawal::with('user')->where('id', request('id'))->pending()->firstOrFail();
        $withdraw->status         = ManageStatus::PAYMENT_CANCEL;
        $withdraw->admin_feedback = request('admin_feedback');
        $withdraw->save();

        $user           = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = showAmount($withdraw->amount) . ' ' . bs('site_cur') . ' refunded from withdrawal cancellation';
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw_reject';
        $transaction->save();

        notify($user, 'WITHDRAW_REJECT', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
            'admin_details'   => request('admin_feedback'),
        ]);

        $toast[] = ['success', 'Withdrawal rejection success'];

        return back()->withToasts($toast);
    }
}
