<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['reference','supplier_id','discount','user_id','store_id','discount_type','type'] ;
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->hasMany(PurchaseProduct::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function setTotalAttribute($val){
        if($this->discount_type === 'percent'){
            $this->total = $this->total_without_discount - (($this->total_without_discount * $this->disconut)/100);
        }else {
            $this->total = $this->total_without_discount -  $this->disconut;
        }
    }
}
