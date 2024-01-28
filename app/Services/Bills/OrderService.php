<?php

namespace App\Services\Bills;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\SiteConfig;
use App\Traits\GlobalTrait;


class OrderService
{

    use GlobalTrait;
    public function getOrders($relation =[])
    {
        $orders = Order::where('type','order')->with($relation);
        $orders = $this->invoiceFilter($orders);
        return $orders;
    }
    public  function getOrderByIdOrReference($idOrReference)
    {
        return Order::where('id', $idOrReference)->orWhere('reference', $idOrReference)->get();
    }
    public  function getOrderByDate($data,$date=null)
    {
        $date = $this->getPeriodData($date);
        $orders = $data->where('created_at', '>=',$date['from'])->orWhere('created_at','=<',$date['to']);
        return $orders;
    }
    public function getOrdersGreaterThan($data, $price)
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
        if (request()->has('reference')&& $request['reference']) {
            return $this->getOrderByIdOrReference(request()->input('reference'));
        }
        if (request()->has('user_id')&& $request['user_id']) {
            $data = $data->where('user_id', $request['user_id']);
        }
        $data = $this->getOrderByDate($data,$request)->get();
        if (request()->has('greater_price') && $request['greater_price']) {
            $data =  $this->getOrdersGreaterThan($data, request()->input('greater_price'));
        }
        if (request()->has('less_price')&& $request['less_price']) {
            $data =  $this->getOrdersLessThan($data, request()->input('less_price'));
        }
        return $data ;
    }
    public function createOrder($data)
    {
        try {
            return Order::create($data);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }


    public function updateOrder($data)
    {
        try {
            return Order::find($data['id'])->update($data);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function deleteOrder($id)
    {
        try {
            return Order::find($id)->delete();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function restoreOrder($id)
    {
        try {
            return Order::onlyTrashed()->find($id)->restore();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function getAllTrashedOrder()
    {
        try {
            return Order::onlyTrashed();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

    public function addProductForInvoice($products, $id)
    {
        try {
          
            foreach ($products['product_id'] as $key => $value) {
                $dataHandle[$value] = [
                    'quantity' => $products['quantity'][$key],
                    'discount_p' => $products['discount_p'][$key],
                    'discount_type_p' => $products['discount_type_p'][$key],
                    'price' => $products['price'][$key],
                    'created_at' => now(),
                    'updated_at'=> now(),
                ];
                Order::find($id)->products()->sync($dataHandle);
            }
            return  true;
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function setOrderNum()
    {
        try {
            $num = SiteConfig::where('key', 'order_number')->first()->value;
            $num++;
            SiteConfig::where('key', 'order_number')->first()->update(['value' => $num]);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
}
