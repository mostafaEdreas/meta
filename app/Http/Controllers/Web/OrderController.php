<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Services\Bills\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $orderService;
    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }
    public function index(){
        $data['orders'] = $this->orderService->getOrders(['user']);
        return view("invoice.order.index",$data);
    }
    public function show($id){
        $data["order"] =Order::with('products')->find($id);
        $data['customers'] = $this->getCustomersAndStores()->customers;
        $data['stores'] = $this->getCustomersAndStores()->stores;
        $data['products'] = $this->getCustomersAndStores()->products;
        return view('invoice.order.show',$data);
    }
    public function create(){
        $data['customers'] = $this->getCustomersAndStores()->customers;
        $data['stores'] = $this->getCustomersAndStores()->stores;
        $data['products'] = $this->getCustomersAndStores()->products;
       return view('invoice.order.create', $data);
    }
    public function store(OrderRequest $invoice ,OrderProductRequest $products){

        try {
            DB::beginTransaction();
            $productsVa = $products->validated();
            $invoiceVa = $invoice->validated();
            $invoiceVa['user_id'] = auth()->user()->id;
            $order_id = Order::create($invoiceVa)->id;
            if ($order_id) {
                $this->orderService->addProductForInvoice($productsVa, $order_id);
                $this->orderService->setOrderNum();
                DB::commit();
               return redirect()->back()->with('success','تم حفظ الفاتوره');
            }
            DB::rollBack();
            return redirect()->back()->withErrors('لم يتم حفظ الفاتوره');
        } catch (Exception $ex) {
            DB::rollBack();
           return $ex->getMessage();
        }
    }
    public function edit($id){
        $data["order"] =Order::with('products')->find($id);
        $data['customers'] = $this->getCustomersAndStores()->customers;
        $data['stores'] = $this->getCustomersAndStores()->stores;
        $data['products'] = $this->getCustomersAndStores()->products;
        return view('invoice.order.edit',$data);
    }

    public function update(OrderRequest $invoice ,OrderProductRequest $products, $id){
        try {
            DB::beginTransaction();
            $productsVa = $products->validated();
            $invoiceVa = $invoice->validated();
            $invoiceVa['user_id'] = auth()->user()->id;
            $order_id = Order::find($id)->update($invoiceVa);
            if ($order_id) {
                $this->orderService->addProductForInvoice($productsVa, $id);
                DB::commit();
               return redirect()->back()->with('success','تم تحديث الفاتوره');
            }
            DB::rollBack();
            return redirect()->back()->withErrors('لم يتم تحديث الفاتوره');

        }
        catch (Exception $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }
    private function getCustomersAndStores(){
        (object) $data['customers'] = Customer::select('id','name')->get();
        (object)$data['stores'] = Store::select('id','name')->get();
        $products =  Product::select('id','name','img')->get();
        (object) $data['products'] = $products->filter(function ($product) {
         return $product->price > 0;});
        return (object)$data ;
    }
}
