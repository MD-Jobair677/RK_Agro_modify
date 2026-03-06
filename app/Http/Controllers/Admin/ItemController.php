<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rules\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;

class ItemController extends Controller
{
    function index()
    {
        $pageTitle = 'Item List';
        $items = Item::searchable(['name', 'code'])->with('category')
                    ->dateFilter()
                    ->orderBy('id','desc')
                    ->latest()
                    ->paginate(getPaginate());
        return view('admin.item.index', compact('pageTitle', 'items'));
    }


    function create()
    {
        $pageTitle = 'Item Create';
        $categories = Category::orderBy('id')->latest()->get();
        return view('admin.item.create', compact('pageTitle', 'categories'));
    }


    function store(Request $request)
    {
        $pageTitle = 'Item Store';

        $request->validate([
            'category_id'       => ['required', 'numeric'],
            'code'              => 'required|string|unique:items,code',
            'name'              => ['required', 'string'],
            'min_stk_level'     => ['required', 'numeric'],
            'uom'               => ['required', 'string'],
            'item_description'  => ['required', 'string'],
            'status'            => ['required', 'in:0,1'],
            'image'             => [File::types(['png', 'jpg', 'jpeg'])],
        ], [
            'code.unique'   => 'The item code has already been taken.'
        ]);

        DB::beginTransaction();
        try {
            $item = new Item();
            $item->category_id = $request->category_id;
            $item->code = $request->code;
            $item->name = $request->name;
            $item->record_level = $request->min_stk_level;
            $item->uom = $request->uom;
            $item->description = $request->item_description;
            $item->status = $request->status;
            if ($request->hasFile('image')) {
                $item->image_path = fileUploader($request->image, getFilePath('customer'));
            }
        // dd($request->all());

            $item->save();
            DB::commit();
            $toast[] = ['success', 'Item create successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Item create failed.'];
            return back()->withToasts($toast);
        }
    }

    function edit($id)
    {
        $pageTitle = 'Item Edit';
        $categories = Category::orderBy('id')->latest()->get();
        $item = Item::findOrFail($id);
        return view('admin.item.edit', compact('pageTitle', 'item', 'categories'));
    }

    function update(Request $request, $id)
    {
        $pageTitle = 'Item Update';
        $item = Item::findOrFail($id);

        $request->validate([
            'category_id'       => ['required', 'numeric'],
            'code'              => ['required', 'string', Rule::unique('items', 'code')->ignore($item->id, 'id')],
            'name'              => ['required', 'string'],
            'min_stk_level'     => ['required', 'numeric'],
            'uom'               => ['required', 'string'],
            'item_description'  => ['required', 'string'],
            'status'            => ['required', 'in:0,1'],
            'image'             => ['nullable', File::types(['png', 'jpg', 'jpeg'])],
        ]);

        DB::beginTransaction();
        try {
            $item->category_id = $request->category_id;
            $item->code = $request->code;
            $item->name = $request->name;
            $item->record_level = $request->min_stk_level;
            $item->uom = $request->uom;
            $item->description = $request->item_description;
            $item->status = $request->status;
            if ($request->hasFile('image')) {
                $item->image_path = fileUploader($request->image, getFilePath('customer'),
                '',$item->image_path);
            }
            $item->save();
            DB::commit();
            $toast[] = ['success', 'Item update successfully'];
            return back()->withToasts($toast);
        } catch (\Exception $exp) {
            DB::rollBack();
            $toast[] = ['error', 'Something went wrong! Item update failed.'];
            return back()->withToasts($toast);
        }
    }


    // function view($id)
    // {
    //     $pageTitle = 'Customer Details';
    //     $customer = Customer::findOrFail($id);
    //     return view('admin.customer.view', compact('pageTitle', 'customer'));
    // }
}
