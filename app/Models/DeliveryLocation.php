<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Constants\ManageStatus;

class DeliveryLocation extends Model
{
    use HasFactory;

        public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
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
        if ($this->status == ManageStatus::NOT_DELIVERED) {
            $html = '<span class="badge bg-label-warning">' . trans('Pending Delivery') . '</span>';
        } elseif ($this->status ==  ManageStatus::DELIVERED) {
            $html = '<span class="badge bg-label-success">' . trans('Delivered') . '</span>';
        }

        return $html;
    }
}
