<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPrints extends Model
{
    use HasFactory;
     protected $fillable = [
        'booking_id',
        'customer_id',
        'print_uid',
        'printed_at',
        'is_print'
    ];


     public function cattles()
    {
        return $this->belongsToMany(
            Cattle::class,
            'booking_print_cattle',
            'booking_print_id',
            'cattle_id'
        );
    }


   public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}
   public function booking()
{
    return $this->belongsTo(Booking::class, 'customer_id');
}





}
