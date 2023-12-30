<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['reference','customer_id','discount','user_id','store_id','discount_type','type'] ;
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->hasMany(PurchaseProduct::class);
    }
    public function custmer(){
        return $this->belongsTo(Customer::class);
    }

}
