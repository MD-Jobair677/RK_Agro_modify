<?php

namespace App\Policies;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class PermissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
          Auth::shouldUse('admin');
    }
    
    public function hasPermission(Admin $admin, string $permissionTitle)
    {
        $admin->loadMissing('roles.permissions');

        return $admin->id == 1 && optional($admin->roles->first())->name === 'super admin'
        || $admin->roles->flatMap->permissions->pluck('name')->contains($permissionTitle);
    }

}
