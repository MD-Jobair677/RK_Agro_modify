<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Role List';
        $roles = Role::searchable(['name'])->dateFilter()->orderBy('id')->latest()->whereNot('id', 1)->whereNot('name', 'super admin')->paginate(getPaginate());
        return view('admin.role-permission.index', compact('pageTitle', 'roles'));
    }


    public function setRolepermissions($id)
    {
        $pageTitle   = 'Set Role Permissions';
        $role = Role::with('permissions')->searchable(['name'])->dateFilter()->orderBy('id')->latest()->where('id', $id)->whereNot('id', 1)->whereNot('name', 'super admin')->first();

        if (!$role) {
            $toast[] = ['error', 'Admin not found.'];
            return back()->withToasts($toast);
        }
        $permissions = Permission::get();
        return view('admin.role-permission.set', compact('pageTitle', 'permissions', 'role'));
    }

    public function setUpdateRolepermissions(Request $request, $id)
    {
        $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'required|numeric|exists:permissions,id',
        ], [
            'permissions.*.numeric' => 'Permission is must be a number.',
            'permissions.*.exists' => 'Selected permission does not exist in the system.',
        ]);
        
        $role = Role::with('permissions')
        ->where('id', $id)
        ->whereNot('id', 1)
        ->whereNot('name', 'super admin')
        ->first();
        
        if (!$role) {
            $toast[] = ['error', 'Role not found for permission update.'];
            return back()->withToasts($toast);
        }
       

        $role->permissions()->sync($request->permissions);

        $toast[] = ['success', 'Role Permission update successfully'];
        return back()->withToasts($toast);
    }
}
