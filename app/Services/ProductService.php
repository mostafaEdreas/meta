<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\DB;

class ProductService
{
    use GlobalTrait;
    public function getProducts()
    {
        $products = Product::query();
        $request = request()->all();
        if ($request) {
            if (array_key_exists('active', $request)) {
                $products = $this->getProductsByStatus($products, $request['active']);
            }
            $products = $this->productsFilter($products, $request);
        }
        return  $products;
    }


    private function productsFilter($products, $request)
    {
        if (request()->filled('name')) {
            $products->where('name', 'like', '%' . request()->input('name') . '%');
        }

        return $products;
    }
    public function saveIamge()
    {
        $img = $this->saveImg('img', 'product');
       return $img ;
    }
    public function removeIamge($product)
    {
        return $this->removeImg('img', 'product');
         
    }

    public function getProductsByStatus($products, $status)
    {
        if ($status == 2) {
            $products->withTrashed();
        } else if ($status == 0) {
            $products->onlyTrashed();
        }
        return $products;
    }
}
