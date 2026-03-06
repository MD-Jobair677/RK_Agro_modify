<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenTotalExpense extends Model
{
    use HasFactory;
    protected $fillable = ['expens_type'];
}
