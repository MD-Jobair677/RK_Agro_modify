<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $this->authorizeForAdmin('has-permission', 'warehouse create');
        $pageTitle = 'Warehouse List';
        $warehouses = Warehouse::searchable(['name'])->dateFilter()->orderBy('id')->latest()->paginate(getPaginate());
        return view('admin.common.wh_index', compact('pageTitle', 'warehouses'));
    }

    public function store(Request $request)
    {
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate([
            'name'           => 'required|string|unique:warehouses,name',
            'status'         => 'required|in:0,1',
        ], [
            'name.required' => 'The warehouse name is required.',
            'name.string'   => 'The warehouse name must be a string.',
            'name.unique'   => 'The warehouse name has already been taken.',
            'status.required' => 'The status is required.',
            'status.in'      => 'The status must be either 0 (inactive) or 1 (active).',
        ]);
        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->status = $request->status;
        $warehouse->save();
        $toast[] = ['success', 'Warehouse create successfully'];
        return back()->withToasts($toast);
    }


    public function update(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate([
            'name' => ['required', 'string', Rule::unique('warehouses', 'name')->ignore($id)],
            'status'         => 'required|in:0,1',
        ], [
            'name.required' => 'The warehouse name is required.',
            'name.string'   => 'The warehouse name must be a string.',
            'name.unique'   => 'The warehouse name has already been taken.',
            'status.required' => 'The status is required.',
            'status.in'      => 'The status must be either 0 (inactive) or 1 (active).',
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->name = $request->name;
        $warehouse->status = $request->status;
        $warehouse->save();
        $toast[] = ['success', 'Warehouse update successfully'];
        return back()->withToasts($toast);
    }
}
