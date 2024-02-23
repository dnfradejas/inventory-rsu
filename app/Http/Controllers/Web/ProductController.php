<?php

namespace App\Http\Controllers\Web;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\ProductRepository;

class ProductController extends Controller
{

    /**
     * @var \App\Models\Repositories\ProductRepository
     */
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display single product
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function displaySingleProduct(Request $request, string $slug)
    {
        $storeId = session()->get(CURRENT_STORE);

        $store = Store::find($storeId);
        if (!$store) {
            abort(404);
        }

        $product = $this->productRepository->getSingleProduct($store, $slug);
        
        $viewData = [
            'product' => $product,
        ];
        return view('web.pages.shop.product-detail', $viewData);
    }
}
