<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, Searchable;

        public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
