<?php

namespace App\Services\User;

use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\DB;

 class UserService{
    use GlobalTrait;
    public function getUsers(){
        $users =User::query();
       $request = request()->all();
        if($request){
            if(array_key_exists('active', $request)){
                $users = $this->getUsersByStatus($users,$request['active']) ;
            }
            $users = $this->userFilterByNameAndAddress($users,$request) ;
        }
        return  $users;
    }
    public function getUserOrders($userId){
        return  User::select('id','name','img','address','phone')->where("id",$userId)
        ->where('type','purchase')
        ->with(['orders'=>function($q){
            $q->select('id','ref','total_without_discount','total','user_id','customer_id')
            ->with(['customer'=>function($q){
                $q->select('id','name');
            }]);
        }]);
    }

    public function getUserOrdersReturned($userId){
        return User::select('id','name','img','address','phone')->where("id",$userId)
        ->where('type','returned')
        ->with(['orders'=>function($q){
            $q->select('id','ref','total_without_discount','total','user_id','customer_id')
            ->with(['customer'=>function($q){
                $q->select('id','name');
            }]);
        }]);
    }

    public function getUserPurchases($userId){
        return User::select('id','name','img','address','phone')->where("id",$userId)
        ->where('type','purchase')
        ->with(['purchases'=>function($q){
            $q->select('id','ref','total_without_discount','total','user_id','supplier_id')
            ->with(['supplier'=>function($q){
                $q->select('id','name');
            }]);
        }]);
    }

    public function getUserPurchasesReturned($userId){
        return User::select('id','name','img','address','phone')->where("id",$userId)
        ->where('type','returned')
        ->with(['purchases'=>function($q){
            $q->select('id','ref','total_without_discount','total','user_id','supplier_id')
            ->with(['supplier'=>function($q){
                $q->select('id','name');
            }]);
        }]);
    }

    public function getUserStores($userId){
        return User::select('id','name','img','address','phone')->where("id",$userId)
        ->with(['stores'=>function($q){
            $q->select('id','name','user_id');
            }]);
    }

    private function userFilterByNameAndAddress($users ,$request){
         $users
        ->where('users.name','like','%'.$request['name'].'%')
        ->orWhere('address','%'.$request['address'].'%')
        ->orWhere('phone','%'.$request['phone'].'%')
        ->orWhere('rule_id','%'.$request['rule_id'].'%')
        ->orWhere('email','%'.$request['email'].'%');
        return $users;
    }
    public function saveIamge($ImageName){
        $img = $this->saveImg($ImageName,'user');
        return auth()->user()->update(['img'=> $img]);
    }
    public function removeIamge($user){
        $this->removeImg(auth()->user()->img,'user');
        return auth()->user()->update(['img'=> '']);
    }

    public function getUsersByStatus($users ,$status){
        if($status == 2){  $users->withTrashed();}
        else if($status == 0){  $users->onlyTrashed();}
        return $users;
    }
    
}

