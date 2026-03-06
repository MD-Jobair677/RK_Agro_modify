<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountHead;
use Illuminate\Http\Request;
use App\Models\AccountSubHead;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AccountSubHeadController extends Controller
{
    public function index()
    {
        $this->authorizeForAdmin('has-permission', 'account_head create');
        $pageTitle = 'Account Head List';
        $accountHeads = AccountHead::orderBy('id')->latest()->get();
        $accountSubHeads = AccountSubHead::searchable(['name'])->dateFilter()->with('head')->orderBy('id')->latest()->paginate(getPaginate());
        return view('admin.accounts.sub_head_index', compact('pageTitle','accountSubHeads', 'accountHeads'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate([
            'head_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $existsInHeads = DB::table('account_heads')->where('acc_head_code', $value)->exists();
                    $existsInSubHeads = DB::table('account_sub_heads')->where('acc_sub_head_code', $value)->exists();
        
                    if ($existsInHeads || $existsInSubHeads) {
                        $fail("The {$attribute} already exists in account heads or account sub heads.");
                    }
                },
            ],
            'name'         => 'required|string|unique:account_heads,name',
            'status'       => 'required|in:0,1',
        ], 
        [
            'name.required'    => 'The account head name is required.',
            'name.string'      => 'The account head name must be a string.',
            'name.unique'      => 'The account head name has already been taken.',
            'head_code.unique' => 'The account head code has already been taken.',
            'status.required'  => 'The status is required.',
            'status.in'        => 'The status must be either 0 (inactive) or 1 (active).',
        ]);
        // dd('hi');
        $accountHead = new AccountSubHead();
        $accountHead->account_head_id = $request->acc_head_id;
        $accountHead->acc_sub_head_code = $request->head_code;
        $accountHead->name              = $request->name;
        $accountHead->description       = $request->description;
        $accountHead->status            = $request->status;
        $accountHead->save();
        $toast[] = ['success', 'Account Head create successfully'];
        return back()->withToasts($toast);
    }


    public function update(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate([
            'head_code' => ['required', Rule::unique('account_sub_heads', 'acc_sub_head_code')->ignore($id)],
            'name'      => ['required', 'string', Rule::unique('account_sub_heads', 'name')->ignore($id)],
            'status'    => 'required|in:0,1',
        ], [
            'name.required'    => 'The account head name is required.',
            'name.string'      => 'The account head name must be a string.',
            'name.unique'      => 'The account head name has already been taken.',
            'head_code.unique' => 'The account head code has already been taken.',
            'status.required'  => 'The status is required.',
            'status.in'        => 'The status must be either 0 (inactive) or 1 (active).',
        ]);

        $accountHead = AccountSubHead::findOrFail($id);
        $accountHead->account_head_id   = $request->acc_head_id;
        $accountHead->acc_sub_head_code = $request->head_code;
        $accountHead->name              = $request->name;
        $accountHead->description       = $request->description;
        $accountHead->status;
        $accountHead->save();
        $toast[] = ['success', 'Cattle Category update successfully'];
        return back()->withToasts($toast);
    }
  
}
