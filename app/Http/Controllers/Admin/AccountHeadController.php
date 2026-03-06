<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountHead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountHeadController extends Controller
{
    public function index()
    {
        $this->authorizeForAdmin('has-permission', 'account_head create');
        $pageTitle = 'Account Head List';
        $accountHeads = AccountHead::searchable(['name'])->dateFilter()->orderBy('id')->latest()->paginate(getPaginate());
        return view('admin.accounts.head_index', compact('pageTitle','accountHeads'));
    }

    public function store(Request $request)
    {
        
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate([
            'head_code'    => 'required|unique:account_heads,acc_head_code',
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

        $accountHead = new AccountHead();
        $accountHead->acc_head_code = $request->head_code;
        $accountHead->name          = $request->name;
        $accountHead->description   = $request->description;
        $accountHead->status        = $request->status;
        $accountHead->save();
        $toast[] = ['success', 'Account Head create successfully'];
        return back()->withToasts($toast);
    }


    public function update(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', ['admin check edit','account_head_access_update']);
        $request->validate([
            'head_code' => ['required', Rule::unique('account_heads', 'acc_head_code')->ignore($id)],
            'name'      => ['required', 'string', Rule::unique('account_heads', 'name')->ignore($id)],
            'status'    => 'required|in:0,1',
        ], [
            'name.required'    => 'The account head name is required.',
            'name.string'      => 'The account head name must be a string.',
            'name.unique'      => 'The account head name has already been taken.',
            'head_code.unique' => 'The account head code has already been taken.',
            'status.required'  => 'The status is required.',
            'status.in'        => 'The status must be either 0 (inactive) or 1 (active).',
        ]);

        $accountHead = AccountHead::findOrFail($id);
        $accountHead->acc_head_code = $request->head_code;
        $accountHead->name          = $request->name;
        $accountHead->description   = $request->description;
        $accountHead->status        = $request->status;
        $accountHead->save();
        $toast[] = ['success', 'Cattle Category update successfully'];
        return back()->withToasts($toast);
    }
  
}
