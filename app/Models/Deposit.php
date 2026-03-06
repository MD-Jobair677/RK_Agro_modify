<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Deposit extends Model
{
    use UniversalStatus, Searchable;

    protected $casts = [
        'details' => 'object',
    ];

    protected $hidden = ['details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
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
            $html = '<span class="badge bg-label-warning">'.trans('Pending').'</span>';
        }
        elseif($this->status == ManageStatus::PAYMENT_SUCCESS && $this->method_code >= 1000){
            $html = '<span class="badge bg-label-success">'.trans('Approved').'</span>';
        }
        elseif($this->status ==  ManageStatus::PAYMENT_SUCCESS && $this->method_code < 1000){
            $html = '<span class="badge badge--success">'.trans('Succeed').'</span>';
        }
        elseif($this->status ==  ManageStatus::PAYMENT_CANCEL){
            $html = '<span class="badge bg-label-danger">'.trans('Rejected').'</span>';
        }else{
            $html = '<span><span class="badge bg-label-secondary">'.trans('Initiated').'</span></span>';
        }
        return $html;
    }

    /**
     * Get the campaign that owns the deposit.
     */


       // scope
    public function scopeGatewayCurrency()
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function scopeBaseCurrency()
    {
        return @$this->gateway->crypto == ManageStatus::ACTIVE ? 'USD' : $this->method_currency;
    }

    public function scopePending()
    {
        return $this->where('method_code','>=',1000)->where('status', ManageStatus::PAYMENT_PENDING);
    }

    public function scopeRejected()
    {
        return $this->where('method_code','>=',1000)->where('status', ManageStatus::PAYMENT_CANCEL);
    }

    public function scopeApproved()
    {
        return $this->where('method_code','>=',1000)->where('status', ManageStatus::PAYMENT_SUCCESS);
    }

    public function scopeSuccessful()
    {
        return $this->where('status', ManageStatus::PAYMENT_SUCCESS);
    }

    public function scopeInitiated()
    {
        return $this->where('status', ManageStatus::PAYMENT_INITIATE);
    }
   

}
