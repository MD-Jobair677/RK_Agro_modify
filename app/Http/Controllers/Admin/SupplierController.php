<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rules\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    function index()
    {
        $pageTitle = 'Supplier List';
        $suppliers = Supplier::searchable(['name', 'phone'])
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->latest()
            ->paginate(getPaginate());
        return view('admin.supplier.index', compact('pageTitle', 'suppliers'));
    }
    function edit($id)
    {
        // $cattleCategories = CattleCategory::where('status', 1)->get();
        $supplier = Supplier::find($id);
        $pageTitle = "Edit ".' '.$supplier->fullname;
        if (!$supplier) {
            $toast[] = ['error', 'Supplier is not valid.'];
            return back()->withToasts($toast);
        }

        return view('admin.supplier.edit', compact('pageTitle', 'supplier'));
    }
       
    function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        if (!$supplier) {
            $toast[] = ['error', 'Supplier is not valid.'];
            return back()->withToasts($toast);
        }

        // dd($request->all());

        $request->validate([
            'firstname'  => ['required', 'string'],
            'lastname'   => ['required', 'string'],
            'email'      => ['required', 'string', 'email', Rule::unique('suppliers', 'email')->ignore($supplier->id, 'id')],
            'phone'      => ['required', 'numeric', Rule::unique('suppliers', 'contact_number')->ignore($supplier->id, 'id')],
            'nid_number' => ['nullable', 'numeric'],
            'address'    => ['required', 'string'],
            'image'      => ['nullable', File::types(['png', 'jpg', 'jpeg'])],
        ]);

        DB::beginTransaction();
        try {
            $supplier->first_name     = $request->firstname;
            $supplier->last_name      = $request->lastname;
            $supplier->email          = $request->email;
            $supplier->contact_number = $request->phone;
            $supplier->nid_number     = $request->nid_number;
            $supplier->address        = $request->address;
            if ($request->hasFile('image')) {
                $supplier->image_path = fileUploader($request->image, getFilePath('supplier'),
                '',$supplier->image_path);
            }
            $supplier->save();
            DB::commit();
            $toast[] = ['success', 'Supplier update successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Supplier update failed.'];
            return back()->withToasts($toast);
        }
    }
}

