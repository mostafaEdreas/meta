<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }
    public function index(){
        $data['products'] = $this->productService->getProducts()->paginate(50)->appends(request()->query());
        return view("product.index",$data);
    }

    public function store(ProductRequest $request){
        $newProduct =  $request->all();
        if (request()->hasFile('img')) {
           $newProduct['img'] = $this->productService->saveIamge();
        }
        $product = Product::create($newProduct);
        if($product){
            return redirect()->back()->with("succ","تم اضافة ". $newProduct['name'].'بنجاح' );
        }
        return redirect()->back()->withErrors('لم يتم اضافة المستخدم ');
    }

    public function distroy($id){
        $distroyProduct = Product::find($id)->delete();
        if(!$distroyProduct){
            return redirect()->back()->withErrors("لم يتم ايقاف الحساب");
        }
        return redirect()->back()->with("succ","تم ايقاف الحساب");

    }
    public function restore($id){
        $restoreProduct = Product::onlyTrashed()->find($id)->restore();
        if(!$restoreProduct){
            return redirect()->back()->withErrors("لم يتم تنشيط الحساب ");
        }
        return redirect()->back()->with("succ","تم تنشيط الحساب");
    }

    public function changeImg(){
       $this->productService->saveIamge();
        return redirect()->back();
    }
}
