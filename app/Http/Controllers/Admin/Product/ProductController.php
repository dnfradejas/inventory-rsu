<?php

namespace App\Http\Controllers\Admin\Product;

use stdClass;
use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\DeliveryDetail;
use App\Models\UnitOfMeasure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    
    

    /**
     * Display listing page
     *
     * @param \App\Repositories\ProductRepository $productRepository
     * 
     * @return \Illuminate\View\View
     */
    public function listing(ProductRepository $productRepository)
    {
        
        $products = $productRepository->get();
        
        $data = [];
        foreach($products as $product){
            if (isset($data[$product->id])) {
                $data[$product->id]->stock = $data[$product->id]->stock + $product->stock;
            } else {
                $data[$product->id] = $product;
            }
        }
        
        $view_data = [
            'products' => $data,
        ];

        if (config('app.env') === 'testing') {
            return response()->json(api_response($products));
        }

        return view('admin.pages.product.listing', $view_data);
    }

    /**
     * Get product details
     *
     * @param integer $id
     * 
     * @return \Illuminate\View\View
     */
    public function getDetail(int $id)
    {
        $details = $this->getDeliveryDetailsQuery()
                        ->where('delivery_details.product_id', $id)
                        ->orderBy('expiration_date', 'asc')
                        ->get();

        $currentStock = 0;
        $lifeTimeSales = 0;
        if (count($details) > 0) {
            $orders = OrderProduct::where('sku', $details[0]->sku)
                                  ->whereBetween('created_at', [date('Y-m-d 00:00:00'), date('Y-12-31 23:59:59')])
                                  ->orderBy('created_at', 'asc')
                                  ->get();
            
            list($lifeTimeSales, $monthlySales) = $this->getProductLifeTimeSales($orders);
            foreach($details as $detail){
                $currentStock += $detail->quantity;
            }
            
            $monthlyNames = array_keys($monthlySales);
            
            $monthlySalesValues = [];
            foreach($monthlySales as $value){
                array_push($monthlySalesValues, $value);
            }
            
            
            return view('admin.pages.product.detail', [
                'details' => $details,
                'currentStock' => $currentStock,
                'lifeTimeSales' => $lifeTimeSales,
                'monthly_names' => $monthlyNames,
                'montly_values' => $monthlySalesValues,
            ]);

        }
    }

    /**
     * Get lifetime sales of a product
     *
     * @param \Illuminate\Database\Eloquent\Collection $orders
     * 
     * @return array<int, array>
     */
    protected function getProductLifeTimeSales($orders)
    {
        $months = get_months();
        
        $items = [];
        $lifeTimeSales = 0;
        foreach($orders as $order){
            $price = $order->discount_price > 0 ? $order->discount_price : $order->price;
            $lifeTimeSales += $price * $order->quantity;

            $monthInt = ((int) date('m', strtotime($order->created_at))) - 1;
            $total = $price * $order->quantity;
            if (isset($items[$monthInt])) {
                $items[$monthInt] += $total;
            } else {
                $items[$monthInt] = $total;
            }
            
        }
        
        $monthlySales = [];
        foreach($months as $idx => $month){
            if (isset($items[$idx])) {
                $monthlySales[$month] = $items[$idx];
            } else {
                $monthlySales[$month] = 0;
            }

        }

        
        return [$lifeTimeSales, $monthlySales];
    }

    /**
     * Display form
     *
     * @param \App\Models\Brand $brand
     * @param \App\Models\Category $category
     * @param \App\Models\UnitOfMeasure $unitOfMeasure
     * @param string|null $slug
     * 
     * @return \Illuminate\View\View
     */
    public function displayForm(
        Brand $brand,
        Category $category,
        UnitOfMeasure $unitOfMeasure,
        string $slug = null)
    {

        $product = $slug ? Product::where('slug', $slug)->whereNull('deleted_at')->first() : new Product();

        $view_data = [
            'categories' => $category->get(),
            'brands' => $brand->get(),
            'uoms' => $unitOfMeasure->get(),
            'product' => $product,
            'cardTitle' => $slug ? 'Update product' : 'Add new product',
        ];

        return view('admin.pages.product.form', $view_data);
    }


    /**
     * Extract and upload image from request
     *
     * @param \App\Http\Requests\Admin\ProductRequest $request
     * 
     * @return string
     */
    protected function extractImageFromRequest(ProductRequest $request)
    {
        if ($request->has('id') && $request->id) {
            // image is not change when product is updated
            if (!$request->hasFile('image')) {
                return null;
            }
        }
        if ($request->hasFile('image')) {
            $upload_path = "/storage" . str_replace('public', "", $request->image->store('public/images'));
            return $upload_path;
        }

        return null;
    }

    /**
     * Create product
     * 
     * @param \App\Http\Requests\Admin\ProductRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(ProductRequest $request)
    {        
        
        try {
            DB::transaction(function() use ($request) {
                
                $id = $request->has('id') && $request->id ? $request->id : null;
                Product::updateOrCreate([
                    'id' => $id
                ], array_filter([
                    'brand_id' => $request->brand,
                    'category_id' => $request->category,
                    'unit_of_measure_id' => $request->unit_of_measure,
                    'product_name' => $request->product_name,
                    'sku' => $request->sku,
                    'slug' => sluggify($request->product_name) . uniqid(),
                    'price' => $request->price,
                    'discount_price' => $request->discount_price ? $request->discount_price : 0,
                    'image' => $this->extractImageFromRequest($request),
                    'status' => $request->status,
                ]));

            });

            return response()->json(api_response('Product successfully saved!'));
        } catch (Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving product.', 'failed', 'Failed', 400), 400);
        }
    }


    /**
     * Delete product
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        
        try {
            DB::transaction(function() use ($request) {
                $id = $request->id;
                Product::where('id', $id)
                       ->update([
                           'deleted_at' => now()->__toString(),
                           'status' => Product::INACTIVE
                       ]);
            });
            return response()->json(api_response('Product successfully deleted.'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while deleting product.', 'failed', 'Failed', 400), 400);
        }
    }

    
    /**
     * Check product price
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\DeliveryDetail $deliveryDetail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckPrice(Request $request, DeliveryDetail $deliveryDetail)
    {

        $price = 0;
        $product = null;
        if ($request->has('q') && $request->q) {
            $product = $this->getDeliveryDetailsQuery()
                            ->where('delivery_details.barcode', 'like', '%' . $request->q . '%')
                            ->orWhere('products.product_name', 'like', '%' . $request->q . '%')
                            ->first();
            
            if ($product) {
                $price = $product->discount_price > 0 ? $product->discount_price : $product->price;
            }
            
        } else {
            // barcode has been cleared from text input
            return response()->json(['html' => null]);
        }

        $html = view('admin.pages.order.modal.checkprice-product-result', [
            'product' => $product,
            'price' => $price,
        ])->render();
        

        return response()->json(['html' => $html]);
        
    }


}
