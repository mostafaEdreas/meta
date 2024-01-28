<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['reference','customer_id','discount','user_id','store_id','discount_type','type'] ;
    protected $appends = [
        'totalInvoiceWithoutDiscount',
        'quantitiesNumber',
        'discountOnProducts',
        'totalInvoiceAfterDiscountOnOnlyProductes',
        'discountOnInvoice',
        'invoiceNet',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){ 
        return $this->belongsToMany(Product::class,'order_products')->withPivot(['quantity','discount_p','discount_type_p','price'])->selectRaw('CASE WHEN discount_type_p = "percent" then (quantity * price) - (quantity *price  * (discount_p/ 100)) ELSE (quantity * price) - (discount_p) END AS total');
    }


    public function custmer(){
        return $this->belongsTo(Customer::class);
    }
    
    
    public function getTotalInvoiceWithoutDiscountAttribute(){
        $amountAndPercent =  $this->amountAndPercent();
        return (float) number_format($amountAndPercent->total, 2, '.', '');
    }

    public function getQuantitiesNumberAttribute(){
        $value = OrderProduct::where('order_id',$this->id)->sum('quantity');
        return  number_format($value, 2, '.', '');
    }

    public function getDiscountOnProductsAttribute():object {

        $amountAndPercent =  $this->amountAndPercent();
        return (object)[
            'amount'=> (float) number_format($amountAndPercent->amount, 2, '.', ''),
            'percent'=> (float) number_format($amountAndPercent->percent, 2, '.', '')
        ];
    }

    public function getTotalInvoiceAfterDiscountOnOnlyProductesAttribute(){
       $net = $this->getTotalInvoiceWithoutDiscountAttribute() - $this->getDiscountOnProductsAttribute()->amount;
        return  number_format($net, 2, '.', '');
    }

    public function getDiscountOnInvoiceAttribute(){
        $discount = $this->discount;
        $type = $this->discount_type;
        $netProducts = $this->getTotalInvoiceAfterDiscountOnOnlyProductesAttribute();
        if ($type == 'amount') {
            $amount = $discount;
            $percent = ($discount / $netProducts) *100;
        }else {
            $amount = ($discount /100) * $netProducts ;
            $percent = $discount;
        }
        return (object)[
            'amount'=>  number_format($amount, 2, '.', ''),
            'percent'=>  number_format($percent, 2, '.', '')
        ];
    }

    public function getInvoiceNetAttribute(){
        $net = $this->getTotalInvoiceAfterDiscountOnOnlyProductesAttribute() - $this->getDiscountOnInvoiceAttribute()->amount;
        return   number_format($net, 2, '.', '');
    }
    private function amountAndPercent(){
        $values =  OrderProduct::where('order_id',$this->id)->get();
        $amount = 0 ;
        $total = 0;
        foreach ($values as $key => $value) {
            if($value->discount_type_p == 'amount'){
                $amount += $value->discount_p;
                $total += $value->price;
            }else {
                $amount += ( $value->quantity  * $value->price * ($value->discount_p/ 100)) ;
                $total += $value->price * $value->quantity;
            }
        }
       
        $percent = ($amount / $total) * 100;
        return (object)[
            'amount'=> number_format($amount, 2, '.', ''),
            'percent'=> number_format($percent, 2, '.', ''),
            'total'=> number_format($total, 2, '.', '')
        ];
    }
}
