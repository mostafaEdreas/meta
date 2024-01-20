<?php

namespace App\Services\Bills;

use App\Models\Purchase;
use App\Models\SiteConfig;
use App\Traits\GlobalTrait;


class PurchaseService
{

    use GlobalTrait;
    public function getPurchases($relation =[])
    {
        $orders = Purchase::where('type','purchase')->with($relation);
        $orders = $this->invoiceFilter($orders);
        return $orders;
    }
    public  function getPurchaseByIdOrReference($idOrReference)
    {
        return Purchase::where('id', $idOrReference)->orWhere('ref', $idOrReference);
    }
    public  function getPurchaseByDate($data,$date)
    {
        $date = $this->getPeriodData($date);
        return $data->where('created_at', '>=',$date['from'])->orWhere('created_at','=<',$date['to']);
    }

    public function getAllPurchases()
    {
        return Purchase::orderBy('created_at', 'desc');
    }
    public function createPurchase($data)
    {
        try {
            return Purchase::create($data);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function getPurchasesGreaterThan($data, $price)
    {
        if($price){
            $data = $data->filter(function ($invoice,) use ($price) {
                return $invoice->invoiceNet > $price;
            });
        }
        return $data;
    }

    public function getOrdersLessThan($data, $price)
    {
        if($price){
            $data = $data->filter(function ($invoice,) use ($price) {
                return $invoice->invoiceNet < $price;
            });   
        }
        return $data;
    }
    public function invoiceFilter($data)
    {
        $request = request()->all();
        if (request()->has('reference') && $request['reference']) {
            return $this->getPurchaseByIdOrReference(request()->input('reference'));
        }
        $data = $this->getPurchaseByDate($data,$request)->get();
        if (request()->has('greater_price')&& $request['greater_price']) {
            $data =  $this->getPurchasesGreaterThan($data, request()->input('greater_price'));
        }
        if (request()->has('less_price')&& $request['less_price']) {
            $data =  $this->getOrdersLessThan($data, request()->input('less_price'));
        }
        return $data ;
    }


    public function updatePurchase($data)
    {
        try {
            return Purchase::find($data['id'])->update($data);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function deletePurchase($id)
    {
        try {
            return Purchase::find($id)->delete();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function restorePurchase($id)
    {
        try {
            return Purchase::onlyTrashed()->find($id)->restore();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function getAllTrashedPurchase()
    {
        try {
            return Purchase::onlyTrashed();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function getTrashedPurchase($id)
    {
        try {
            return Purchase::onlyTrashed()->find($id);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function addProductForInvoice($products, $id)
    {
        try {
            $dataHandle = [];
            foreach ($products['product_id'] as $key => $value) {
                $dataHandle[$value] = [
                    'quantity' => $products['quantity'][$key],
                    'discount_p' => $products['discount_p'][$key],
                    'discount_type_p' => $products['discount_type_p'][$key],
                    'purchase_price' => $products['purchase_price'][$key],
                    'sale_price' => $products['sale_price'][$key],
                    'created_at' => now(),
                    'updated_at'=> now(),
                ];
                Purchase::find($id)->products()->sync($dataHandle);
            }
            return  true;
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function setPurchaseNum()
    {
        try {
            $num = SiteConfig::where('key', 'purchase_number')->first()->value;
            $num++;
            SiteConfig::where('key', 'purchase_number')->first()->update(['value' => $num]);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
}
