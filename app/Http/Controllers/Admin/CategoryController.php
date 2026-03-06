<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorizeForAdmin('has-permission', 'category list');
        $pageTitle = 'Category List';
        $categories = Category::searchable(['name'])->dateFilter()->orderBy('id')->latest()->paginate(getPaginate());
        return view('admin.common.cate_index', compact('pageTitle','categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeForAdmin('has-permission', 'category create');
        $request->validate([
            'name'           => 'required|string|unique:categories,name',
            'status'         => 'required|in:0,1',
        ], [
            'name.required' => 'The category name is required.',
            'name.string'   => 'The category name must be a string.',
            'name.unique'   => 'The category name has already been taken.',
            'status.required' => 'The status is required.',
            'status.in'      => 'The status must be either 0 (inactive) or 1 (active).',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();
        $toast[] = ['success', 'Category create successfully'];
        return back()->withToasts($toast);
    }


    public function update(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', 'category edit');
        $request->validate([
            'name' => ['required', 'string', Rule::unique('categories', 'name')->ignore($id)],
            'status'         => 'required|in:0,1',
        ], [
            'name.required' => 'The category name is required.',
            'name.string'   => 'The category name must be a string.',
            'name.unique'   => 'The category name has already been taken.',
            'status.required' => 'The status is required.',
            'status.in'      => 'The status must be either 0 (inactive) or 1 (active).',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();
        $toast[] = ['success', 'Category update successfully'];
        return back()->withToasts($toast);
    }
  
}
