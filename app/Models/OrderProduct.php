<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['product_id','order_id','price','quantity','discount','discount_type'] ;

    

}
