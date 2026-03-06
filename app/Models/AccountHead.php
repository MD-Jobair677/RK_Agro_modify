<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountHead extends Model
{
    use HasFactory, Searchable;

    public function subHeads()
    {
        return $this->hasMany(AccountSubHead::class);
    }
    public function expenses()
    {
        return $this->hasMany(GeneralExpense::class)->active();
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }

    public function badgeData(){
        $html = '';
       if($this->status == ManageStatus::CATEGORY_ACTIVE){
            $html = '<span class="badge bg-label-success">'.trans('Active').'</span>';
        }
        elseif($this->status ==  ManageStatus::CATEGORY_INACTIVE){
            $html = '<span class="badge bg-label-danger">'.trans('Inactive').'</span>';
        }
        
        return $html;
    }
}
