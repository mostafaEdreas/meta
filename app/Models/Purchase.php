<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{

    use HasFactory,SoftDeletes;
    
    protected $fillable = ['reference','supplier_id','discount','user_id','store_id','discount_type','type'] ;
    
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
        return $this->belongsToMany(Product::class,'purchase_products')->withPivot(['quantity','discount_p','discount_type_p','purchase_price','sale_price'])->selectRaw('CASE WHEN discount_type_p = "percent" then (quantity * purchase_price) - (quantity *purchase_price  * (discount_p/ 100)) ELSE (quantity * purchase_price) - (discount_p) END AS total');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function getTotalInvoiceWithoutDiscountAttribute(){
        $amountAndPercent =  $this->amountAndPercent();
        return (float) number_format($amountAndPercent->total, 2, '.', '');
    }

    public function getQuantitiesNumberAttribute(){
        $value = PurchaseProduct::where('purchase_id',$this->id)->sum('quantity');
        return (float) number_format($value, 2, '.', '');
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
        return  (float) number_format($net, 2, '.', '');
    }

    public function getDiscountOnInvoiceAttribute(){
        $discount = $this->discount;
        $type = $this->discount_type;
        $netProducts = $this->getTotalInvoiceAfterDiscountOnOnlyProductesAttribute();
        if ($type == 'amount') {
            $amount = $discount;
            $percent = ($discount / $netProducts) *100;
        }else {
            $amount = ($discount * $netProducts) /100 ;
            $percent = $discount;
        }
        return (object)[
            'amount'=> (float) number_format($amount, 2, '.', ''),
            'percent'=> (float) number_format($percent, 2, '.', '')
        ];
    }

    public function getInvoiceNetAttribute(){
        $net = $this->getTotalInvoiceAfterDiscountOnOnlyProductesAttribute() - $this->getDiscountOnInvoiceAttribute()->amount;
        return  (float) number_format($net, 2, '.', '');
    }
    private function amountAndPercent(){
        $values =  PurchaseProduct::where('purchase_id',$this->id)->get();
        $amount = 0 ;
        $total = 0;
        foreach ($values as $key => $value) {
            if($value->discount_type_p == 'amount'){
                $amount += $value->discount_p ;
                $total += $value->purchase_price;
            }else {
                $amount += $value->purchase_price  * $value->quantity  * ($value->discount_p / 100);
                $total += $value->purchase_price * $value->quantity;
            }
        }
        $percent = ($amount / $total) * 100;
        return (object)[
            'amount'=> (float) number_format($amount, 2, '.', ''),
            'percent'=> (float) number_format($percent, 2, '.', ''),
            'total'=> (float) number_format($total, 2, '.', '')
        ];
    }
}
