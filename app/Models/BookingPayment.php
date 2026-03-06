<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Models\CattleBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BookingPayment extends Model
{
    use HasFactory, Searchable;

    public function cattleBooking(){
        return $this->belongsTo(CattleBooking::class,'cattle_id','id');
    }



    public function booking(){
     return $this->belongsTo(Booking::class,'cattle_booking_id','id');
    }

}
