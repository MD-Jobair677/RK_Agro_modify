<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Admin extends Authenticatable
{

    use HasApiTokens,HasFactory, Searchable;
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function roles(){
       return $this->belongsToMany(Role::class,'admin_role','admin_id','role_id');
    }

    public function hasRole($role){
       return $this->roles->contains('name',$role);
    }
}
