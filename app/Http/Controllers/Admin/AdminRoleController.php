<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AdminRoleController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Admin List';
        $admins = Admin::with('roles')->searchable(['name'])->dateFilter()->orderBy('id')->latest()->whereNot('id', 1)->whereNot('name', 'super admin')->paginate(getPaginate());
        return view('admin.admin-role.index', compact('pageTitle', 'admins'));
    }

    public function setRoles($id)
    {
        $pageTitle   = 'Set Admin Role';
        $admin = Admin::with('roles')->searchable(['name'])->dateFilter()->orderBy('id')->latest()->where('id', $id)->whereNot('id', 1)->whereNot('name', 'Super Admin')->first();

        if (!$admin) {
            $toast[] = ['error', 'Admin not found.'];
            return back()->withToasts($toast);
        }
        $roles = Role::whereNot('id', 1)->whereNot('name', 'super admin')->get();
        return view('admin.admin-role.set', compact('pageTitle', 'admin', 'roles'));
    }

    public function setUpdateRoles(Request $request, $id)
    {
        $request->validate([
            'roles'   => 'required|array',
            'roles.*' => [
                'required',
                'numeric',
                'exists:roles,id',
                Rule::notIn([1]), // Prevents assigning Role ID = 1 because is super admin
            ],
        ], [
            'roles.*.numeric' => 'Role is must be a number.',
            'roles.*.exists' => 'Selected role does not exist in the system.',
        ]);

        $admin = Admin::with('roles')
            ->where('id', $id)
            ->whereNot('id', 1)
            ->whereNot('name', 'Super Admin')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('roles.id', 1); 
            })->first();

        if (!$admin) {
            $toast[] = ['error', 'Admin not found or not role editable.'];
            return back()->withToasts($toast);
        }

        $admin->roles()->sync($request->roles);

        $toast[] = ['success', 'Admin role update successfully'];
        return back()->withToasts($toast);
    }
}
