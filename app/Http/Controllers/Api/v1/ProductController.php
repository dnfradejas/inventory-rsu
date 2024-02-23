<?php

namespace App\Http\Controllers\Api\v1;


use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\ProductRepository;

class ProductController extends Controller
{


    /**
     * @var \App\Models\Store
     */
    protected $store;

    /**
     * @var \App\Models\Repositories\ProductRepository
     */
    protected $productRepository;

    public function __construct(Store $store, ProductRepository $productRepository)
    {
        $this->store = $store;
        $this->productRepository = $productRepository;
    }


    /**
     * Get list of products
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $request->validate([
            'store_id' => 'required'
        ]);
        $storeId = $request->store_id;
        
        $products = $this->productRepository->retrieveStoreProducts($storeId, $request->toArray());
        
        return response()->json([
            'products' => $products,
        ]);
    }


    /**
     * Display single product
     * 
     * @param int $store_id
     * @param string $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductDetail(int $store_id, string $slug)
    {

        $store = Store::find($store_id);
        if (!$store) {
            return response()->json([
                'product' => []
            ]);
        }

        $product = $this->productRepository->getSingleProduct($store, $slug);
        
        return response()->json([
            'product' => $product
        ]);
    }
}
