<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{   
    use UniversalStatus, Searchable;

    protected $casts = [
        'withdraw_information' => 'object'
    ];

    protected $hidden = [
        'withdraw_information'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function method()
    {
        return $this->belongsTo(WithdrawMethod::class, 'method_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }

    public function badgeData(){
        $html = '';
        if($this->status == ManageStatus::PAYMENT_PENDING){
            $html = '<span class="badge badge--warning">'.trans('Pending').'</span>';
        }elseif($this->status == ManageStatus::PAYMENT_SUCCESS){
            $html = '<span class="badge badge--success">'.trans('Approved').'</span>';
        }elseif($this->status == ManageStatus::PAYMENT_CANCEL){
            $html = '<span class="badge badge--danger">'.trans('Rejected').'</span>';
        }
        return $html;
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_CANCEL);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_SUCCESS);
    }

    public function scopeIndex($query)
    {
        return $query->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function scopeInitiate($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_INITIATE);
    }
}
