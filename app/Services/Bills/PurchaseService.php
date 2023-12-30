<?php

namespace App\Services\Bills;

use App\Models\Purchase;
use App\Traits\GlobalTrait;


 class PurchaseService{

    use GlobalTrait;
    public  function getPurchaseByIdOrReference($idOrReference){
        return Purchase::where('id',$idOrReference)->orWhere('ref',$idOrReference);
    }
    public  function getPurchaseByDate($date){
    $date = $this->getPeriodData($date);
    return Purchase::whereBetween('created_at',[$date['from'],$date['to']])->orderBy('created_at','asc');
    }

    public function getAllPurchases(){
        return Purchase::orderBy('created_at','desc');
    }
    public function createPurchase($data){
        try {
            return Purchase::create($data);
        } catch (\Exception $ex) {
           dd($ex->getMessage());
        }
    }

    
    public function updatePurchase($data){
        try {
            return Purchase::find($data['id'])->update($data);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function deletePurchase($id){
        try {
            return Purchase::find($id)->delete();
        }
        catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function restorePurchase($id){
        try {
            return Purchase::onlyTrashed()->find($id)->restore();
        }
        catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function getAllTrashedPurchase(){
        try {
            return Purchase::onlyTrashed();
        }
        catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    public function getTrashedPurchase($id){
        try {
            return Purchase::onlyTrashed()->find($id);
        }
        catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }


 }