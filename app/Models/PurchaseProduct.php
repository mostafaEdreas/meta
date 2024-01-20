<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProduct extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['purchase_id','product_id','quantity','purchase_price','sale_price','discount','discount_type'] ;
    
}
