<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CattleCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CattleCategoryController extends Controller
{
    public function index()
    {
        $this->authorizeForAdmin('has-permission', 'cattle category');
        $pageTitle = 'Cattle Category List';
        $categories = CattleCategory::searchable(['name'])->dateFilter()->orderBy('id')->latest()->paginate(getPaginate());
        return view('admin.common.cattle_index', compact('pageTitle','categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeForAdmin('has-permission', 'cattle category create');
        $request->validate([
            'name'         => 'required|string|unique:cattle_categories,name',
            'cattle_group' => 'required|in:1,2',
            'status'       => 'required|in:0,1',
        ], [
            'name.required'         => 'The category name is required.',
            'name.string'           => 'The category name must be a string.',
            'name.unique'           => 'The category name has already been taken.',
            'cattle_group.required' => 'The cattle group is required.',
            'status.required'       => 'The status is required.',
            'status.in'             => 'The status must be either 0 (inactive) or 1 (active).',
        ]);
      
        $category = new CattleCategory();
        $category->name         = $request->name;
        $category->cattle_group = $request->cattle_group;
        $category->status       = $request->status;
        $category->save();
        $toast[] = ['success', 'Cattle Category create successfully'];
        return back()->withToasts($toast);
    }


    public function update(Request $request, $id)
    {
        $this->authorizeForAdmin('has-permission', 'cattle category edit');
        $this->authorizeForAdmin('has-permission', 'admin check edit');
        $request->validate([
            'name' => ['required', 'string', Rule::unique('cattle_categories', 'name')->ignore($id)],
            'cattle_group' => 'required|in:1,2',
            'status'         => 'required|in:0,1',
        ], [
            'name.required' => 'The category name is required.',
            'name.string'   => 'The category name must be a string.',
            'name.unique'   => 'The category name has already been taken.',
            'cattle_group.required' => 'The cattle group is required.',
            'status.required' => 'The status is required.',
            'status.in'      => 'The status must be either 0 (inactive) or 1 (active).',
        ]);

        // dd($request->all());
        $category = CattleCategory::findOrFail($id);
        $category->name = $request->name;
        $category->cattle_group = $request->cattle_group;
        $category->status = $request->status;
        $category->save();
        $toast[] = ['success', 'Cattle Category update successfully'];
        return back()->withToasts($toast);
    }
  
}
