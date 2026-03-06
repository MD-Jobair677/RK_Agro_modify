<?php

namespace App\Models;

use App\Models\Cattle;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, Searchable;
    protected $appends = ['full_name']; 


    public function cattle_bookings(){
        return $this->hasMany(CattleBooking::class,'customer_id','id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function bookingPrints()
{
    return $this->hasMany(BookingPrints::class, 'customer_id');
}

 public function delivery_location()
    {
        return $this->belongsTo(DeliveryLocation::class,'booking_id');
    }



}
