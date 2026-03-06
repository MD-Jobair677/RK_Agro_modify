<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryStore extends Model
{
    use HasFactory, Searchable;

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
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
