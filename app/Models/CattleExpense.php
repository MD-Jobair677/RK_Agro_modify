<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CattleExpense extends Model
{
    use HasFactory;

    protected $fillable = [
    'cattle_id',
    'total_cost',
    'last_date',
];
}
