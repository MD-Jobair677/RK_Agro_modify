<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CattleBooking extends Model
{
    use HasFactory;


    protected $fillable = [
        'payment_method'
    ];

    public function cattle()
    {
        return $this->belongsTo(Cattle::class, 'cattle_id', 'id');
    }

     public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function bookingPayment()
    {
        return $this->belongsTo(BookingPayment::class, 'booking_payment_id', 'id');
    }
}
