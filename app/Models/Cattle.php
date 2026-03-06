<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cattle extends Model
{
    use HasFactory, Searchable;
    protected $table = 'cattles';

    protected $fillable = [
        'status',
    ];


    public function cattleCategory()
    {
        return $this->belongsTo(CattleCategory::class, 'cattle_category_id', 'id');
    }

    public function cattle_images()
    {
        return $this->hasMany(CattleImage::class, 'cattle_id', 'id');
    }

    public function primaryImage()
    {
        return $this->hasOne(CattleImage::class, 'cattle_id', 'id')->orderBy('id', 'asc');
    }

    public function primaryCattleRecord()
    {
        return $this->hasOne(CattleRecord::class, 'cattle_id', 'id')->where('is_opening', 1)->orderBy('id', 'asc');
    }

    public function lastCattleRecord()
    {
        return $this->hasOne(CattleRecord::class, 'cattle_id', 'id')->where('valid_until_date', null)->orderBy('id', 'desc');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'cattle_id', 'id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'cattle_id', 'id');
    }

    public function cattle_expenses()
    {
        return $this->hasMany(CattleExpense::class, 'cattle_id', 'id');
    }

    public function getTotalCostAttribute()
    {
        return $this->cattle_expenses()->sum('total_cost');
    }



  public function bookingPrints()
    {
        return $this->belongsToMany(
            BookingPrints::class,
            'booking_print_cattle',
            'cattle_id',
            'booking_print_id'
        );
    }
    

        public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }





    public function badgeData(){
        $html = '';
       if($this->status == ManageStatus::CATTLE_ACTIVE){
            $html = '<span class="badge bg-label-success">'.trans('Active').'</span>';
        }
        elseif($this->status ==  ManageStatus::CATTLE_BOOKED){
            $html = '<span class="badge bg-label-warning">'.trans('Booked').'</span>';
        }
        elseif($this->status ==  ManageStatus::CATTLE_DELIVERED){
            $html = '<span class="badge bg-label-soft">'.trans('Delivered').'</span>';
        }
        elseif($this->status ==  ManageStatus::CATTLE_DIE){
            $html = '<span class="badge bg-label-danger">'.trans('Died').'</span>';
        }
        
        return $html;
    }
}
