<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseProductRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use App\Services\Bills\PurchaseService;
use App\Traits\GlobalTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use GlobalTrait ;
    private $purchaseService;
    public function __construct(PurchaseService $purchaseService){
        $this->purchaseService = $purchaseService;
    }
    public function index(){
        $data['users']= User::select('id','name')->get();
        $data['purchases'] = $this->purchaseService->getPurchases(['user']);
        return view("invoice.purchase.index",$data);
    }
    public function show($id){
        $data["purchase"] =Purchase::with('products')->find($id);
        $data['suppliers'] = $this->getSuppliersAndStores()->suppliers;
        $data['stores'] = $this->getSuppliersAndStores()->stores;
        $data['products'] = $this->getSuppliersAndStores()->products;
        return view('invoice.purchase.show',$data);
    }
    public function create(){
        $data['products'] =  Product::select('id','name','img')->get();
        $data['suppliers'] = Supplier::select('id','name')->get();
        $data['stores'] = Store::select('id','name')->get();
        return view('invoice.purchase.create', $data);
     }
    public function store(PurchaseRequest $invoice ,PurchaseProductRequest $products){
        try {
            DB::beginTransaction();
            $productsVa = $products->validated();
            $invoiceVa = $invoice->validated();
            $invoiceVa['user_id'] = auth()->user()->id;
            $purchase_id = Purchase::create($invoiceVa)->id;
            if ($purchase_id) {
                $this->purchaseService->addProductForInvoice($productsVa, $purchase_id);
                $this->purchaseService->setPurchaseNum();
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
        $data["purchase"] =Purchase::with('products')->find($id);
        $data['suppliers'] = $this->getSuppliersAndStores()->suppliers;
        $data['stores'] = $this->getSuppliersAndStores()->stores;
        $data['products'] = $this->getSuppliersAndStores()->products;
        return view('invoice.purchase.edit',$data);
    }

    public function update(PurchaseRequest $invoice ,PurchaseProductRequest $products,$id){
        try {
            DB::beginTransaction();
            $productsVa = $products->validated();
            $invoiceVa = $invoice->validated();
            $invoiceVa['user_id'] = auth()->user()->id;
            $purchase_id = Purchase::find($id)->update($invoiceVa);
            if ($purchase_id) {
                $this->purchaseService->addProductForInvoice($productsVa, $id);
                DB::commit();
               return redirect()->back()->with('success','تم تحديث الفاتوره');
            }
            DB::rollBack();
            return redirect()->back()->withErrors('لم يتم تحديث الفاتوره');
        } catch (Exception $ex) {
            DB::rollBack();
           return $ex->getMessage();
        }
    }
    private function getSuppliersAndStores(){
        (object) $data['suppliers'] = Supplier::select('id','name')->get();
        (object)$data['stores'] = Store::select('id','name')->get();
        $products =  Product::select('id','name','img')->get();
        (object) $data['products'] = $products->filter(function ($product) {
         return $product->price > 0;});
        return (object)$data ;
    }


}
