<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [

        'booking_id',
        'payment_uid',
        'receipt_tk',
        'comment',
        'printed_at',
        'cattle_booking_id'
        
    ];



    function booking(){
        return $this->belongsTo(Booking::class,'booking_id');
    }
}
