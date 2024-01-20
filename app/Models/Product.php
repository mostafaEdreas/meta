<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $appends = ['price'];
    protected $fillable = ['name','img'];

    public function Orders(){
        return $this->hasMany(Order::class);
    }

    public function purchases($data){
        return $this->belongsToMany(Purchase::class,'purchase_products')->withPivot(['quantity','discount_p','discount_type_p','purchase_price','sale_price']);
    }
    public function getPriceAttribute(){
        $price = PurchaseProduct::where('product_id', $this->id)->latest('id')->first();
        if ($price) {
            return $price->sale_price;
        }
        return '0';
    }
}
