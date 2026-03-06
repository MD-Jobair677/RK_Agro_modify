<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory, Searchable;

    public function booking_payments()
    {
        return $this->hasMany(BookingPayment::class, 'cattle_booking_id', 'id');
    }

    public function cattle_bookings()
    {
        return $this->hasMany(CattleBooking::class, 'booking_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function delivery_location(){
        return $this->hasOne(DeliveryLocation::class,'booking_id','id');
    }

    public function bookingNumberGroupPrices($bookingNumber){
        return $this->where('booking_number',$bookingNumber)->sum('sale_price');
    }
    public function cattle()
    {
        return $this->belongsTo(Cattle::class, 'cattle_id', 'id');
    }
    
    public function delevery()
    {
        return $this->hasOne(DeliveryLocation::class);
    }


    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->badgeData(),
        );
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == ManageStatus::BOOKING_PENDING) {
            $html = '<span class="badge bg-label-warning">' . trans('Pending') . '</span>';
        } elseif ($this->status ==  ManageStatus::BOOKING_DELIVERED) {
            $html = '<span class="badge bg-label-success">' . trans('Delivered') . '</span>';
        } elseif ($this->status ==  ManageStatus::BOOKING_CANCELED) {
            $html = '<span class="badge bg-label-danger">' . trans('Inactive') . '</span>';
        }

        return $html;
    }




    // 
}
