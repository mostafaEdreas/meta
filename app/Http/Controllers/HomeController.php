<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Services\Bills\OrderService;
use App\Services\Bills\PurchaseService;
use App\Services\User\UserService;

class HomeController extends Controller
{
    private $orderService ;
    private $purchaseService ;
    private $userService ;
    public function __construct(
        OrderService $orderService,
        PurchaseService $purchaseService,
        UserService $userService,
    ){
        $this->orderService = $orderService;
        $this->purchaseService = $purchaseService;
        $this->userService = $userService;
    }
    public function index(){
        $ordersToday = $this->orderService->getOrderByDate(Order::query(),['from'=>now(),'to'=>now()])->where('type','ordre');
        $purchasesToday = $this->purchaseService->getPurchaseByDate(Purchase::query(),['from'=> now(),'to'=> now()])->where('type','purchase');
        $adminUsers = $this->userService->getUsers()->where('rule_id',2);
        $superAdminUsers = $this->userService->getUsers()->where('rule_id',3);
        $salerUsers = $this->userService->getUsers()->where('rule_id',1);
        (object) $data['carts']=[
            (object)['route'=>'order.index','name'=>'فواتير مبيعات اليوم','count'=>$ordersToday->count()],
            (object)['route'=>'purchase.index','name'=>'فواتير مشتريات اليوم','count'=>$purchasesToday->count()],
            (object)['route'=>'user.index','name'=>'عدد البائعين','count'=>$salerUsers->count()],
            (object)['route'=>'user.index','name'=>'عدد المديرين','count'=>$adminUsers->count()],
            (object)['route'=>'user.index','name'=>'عدد رؤساء المديرين','count'=>$superAdminUsers->count()],
        ];
       
        return view("home",$data);
    }
}
