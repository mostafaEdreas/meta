<?php

namespace App\Services\Bills;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Traits\GlobalTrait;


class OrderService
{

    use GlobalTrait;
    public  function getOrderByIdOrReference($idOrReference)
    {
        return Order::where('id', $idOrReference)->orWhere('ref', $idOrReference);
    }
    public  function getOrderByDate($date)
    {
        $date = $this->getPeriodData($date);
        $orders = Order::whereBetween('created_at', [$date['from'], $date['to']])->orderBy('created_at', 'asc');
        return $orders;
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

    public function setTatals($orders)
    {

        foreach ($orders as $order) {
            foreach ($order->products as $Product) {
                $order->discount_value = 0;
                if ($Product->discount_type == 'percent') {
                    
                    $order->discount_value += ($Product->purchase_price * $Product->quantity) - ((($Product->purchase_price * $Product->quantity) * $Product->discount) / 100);
                } else {
                    $order->discount_value += (($Product->purchase_price * $Product->quantity) - $Product->discount);
                }
            }
        }
        return $orders ;
    }
}
