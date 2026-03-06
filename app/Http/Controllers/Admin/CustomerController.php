<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\File;

class CustomerController extends Controller
{
    function index()
    {
        $pageTitle = 'Customer List';
        $customers = Customer::searchable(['first_name', 'last_name'])
            ->dateFilter()
            ->orderBy('id','desc')
            ->latest()
            ->paginate(getPaginate());
        return view('admin.customer.index', compact('pageTitle', 'customers'));
    }


    function create()
    {
        $pageTitle = 'Customer Create';
        return view('admin.customer.create', compact('pageTitle'));
    }


    function store(Request $request)
    {
        $pageTitle = 'Customer Store';

        $request->validate([
            'firstname'   => ['required', 'string'],
            'lastname'   => ['required', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('customers', 'email')],
            'phone'   => ['required', 'numeric'],
            'nid_number'   => ['nullable', 'numeric', Rule::unique('customers', 'nid_number')],
            'customer_address'   => ['required', 'string'],
            'image'    => ['required', File::types(['png', 'jpg', 'jpeg'])],
        ]);

        DB::beginTransaction();
        try {
            $customer = new Customer();
            $customer->first_name = $request->firstname;
            $customer->last_name = $request->lastname;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->nid_number = $request->nid_number;
            $customer->address = $request->customer_address;
            if ($request->hasFile('image')) {
                $customer->image_path = fileUploader($request->image, getFilePath('customer'));
            }
            $customer->save();
            DB::commit();
            $toast[] = ['success', 'Customer create successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Customer update failed.'];
            return back()->withToasts($toast);
        }
    }

    function edit($id)
    {
        $pageTitle = 'Customer Edit';
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit', compact('pageTitle', 'customer'));
    }

    function update(Request $request, $id)
    {
        $pageTitle = 'Customer Update';
        $customer = Customer::findOrFail($id);

        $request->validate([
            'firstname'   => ['required', 'string'],
            'lastname'   => ['required', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('customers', 'email')->ignore($customer->id, 'id')],
            'phone'   => ['required', 'numeric'],
            'nid_number'   => ['nullable', 'numeric'],
            'customer_address'   => ['required', 'string'],
            'image'    => ['nullable', File::types(['png', 'jpg', 'jpeg'])],
        ]);

        DB::beginTransaction();
        try {
            $customer->first_name = $request->firstname;
            $customer->last_name = $request->lastname;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->nid_number = $request->nid_number;
            $customer->address = $request->customer_address;
            if ($request->hasFile('image')) {
                $customer->image_path = fileUploader($request->image, getFilePath('customer'),
                '',$customer->image_path);
            }
            $customer->save();
            DB::commit();
            $toast[] = ['success', 'Customer update successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Customer update failed.'];
            return back()->withToasts($toast);
        }
    }


    function view($id)
    {
        $pageTitle = 'Customer Details';
        $customer = Customer::findOrFail($id);
        return view('admin.customer.view', compact('pageTitle', 'customer'));
    }
}
