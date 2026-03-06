<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;


class RoleController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Role List';
        $roles = Role::searchable(['name'])->dateFilter()->orderBy('id')->latest()->whereNot('id', 1)->whereNot('name', 'super admin')->paginate(getPaginate());
        return view('admin.role.index', compact('pageTitle', 'roles'));
    }

    public function create()
    {
        $pageTitle   = 'Role Create';
        return view('admin.role.create', compact('pageTitle'));
    }


    public function store(Request $request)
    {
        $pageTitle   = 'Role Store';
        $request->merge([
            'name' => strtolower($request->name)
        ]);

        $request->validate([
            'name'         => 'required|string|lowercase|unique:roles,name',
            'description'  => 'required|string',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        $toast[] = ['success', 'Role Create Successfully'];
        return back()->withToasts($toast);
    }

    public function edit($id)
    {
        $role = Role::where('id', '!=', 1)
            ->where('name', '!=', 'super admin')
            ->where('id', $id)
            ->first();

        if (!$role) {
            $toast[] = ['error', 'Role not found or not editable.'];
            return back()->withToasts($toast);
        }
        $pageTitle = 'Role edit ' . $role->name;
        return view('admin.role.edit', compact('pageTitle', 'role'));
    }


    public function update(Request $request, $id)
    {

        $request->merge([
            'name' => strtolower($request->name)
        ]);

        $request->validate([
            'name' => ['required', 'string', 'lowercase', Rule::unique('roles', 'name')->ignore($id)],
            'description'  => 'required|string',
        ]);

        $role = Role::where('id', '!=', 1)
            ->where('name', '!=', 'super admin')
            ->where('id', $id)
            ->first();

        if (!$role) {
            $toast[] = ['error', 'Role not found or not updatable.'];
            return back()->withToasts($toast);
        }

        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        $toast[] = ['success', 'Role update successfully'];
        return back()->withToasts($toast);
    }


    public function remove($id)
    {
        $role = Role::where('id', '!=', 1)
            ->where('name', '!=', 'super admin')
            ->where('id', $id)
            ->first();

        if (!$role) {
            $toast[] = ['error', 'Role not found or not updatable.'];
            return back()->withToasts($toast);
        }

        $role->permissions()->detach();
        $role->delete();

        $toast[] = ['success', 'Role Delete successfully'];
        return back()->withToasts($toast);
    }
}
