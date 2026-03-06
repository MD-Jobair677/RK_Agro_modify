<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;


class Role extends Model
{
    use HasFactory, Searchable;

    public function admins()
    {
        return $this->belongsToMany(Admin::class,'admin_role','admin_id','role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_role','role_id','permission_id');
    }
  
    public function hasPermissions($permission)
    {
        return $this->permissions->contains('name', $permission);
    }
}
