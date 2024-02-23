<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Cart;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CartRequest;
use App\Exceptions\AddToCartException;
use App\Models\Repositories\ProductRepository;

class CartController extends Controller
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
     * Add item to cart
     *
     * @param \App\Http\Requests\Web\CartRequest $request
     * 
     * @return mixed
     */
    public function postAdd(CartRequest $request)
    {
        $user = web_user();
        try {

            $this->assertValidRequest($request);
            
            DB::transaction(function() use ($request, $user) {
                $storeId = session()->get(CURRENT_STORE);
                $findCart = Cart::where('product_id', $request->product)
                                 ->where('user_id', $user->id)
                                 ->where('store_id', $storeId)
                                 ->first();
                $quantity = 1;
                if ($findCart) {
                    $quantity = $findCart->quantity + 1;
                }
                
                Cart::updateOrCreate([
                    'product_id' => $request->product,
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                ],
                [
                    'user_id' => $user->id,
                    'product_id' => $request->product,
                    'size_id' => $request->has('size') && $request->size ? $request->size : null,
                    'color_id' => $request->has('color') && $request->color ? $request->color : null,
                    'quantity' => $quantity,
                ]);
            });
            return response()->json(['message' => 'Product has been added to your cart!']);
        } catch(AddToCartException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        } catch(Exception $e) {
            return response()->json([
                'message' => 'Oopps! Unable to add this product to cart.'
            ], 500);
        }
    }

    /**
     * Validate that the product being added to cart is valid
     * 
     * User needs to choose a color and size if product
     * has this variant
     * 
     * @param App\Http\Requests\Web\CartRequest $request
     *
     * @return void
     * 
     * @throws \Exception
     */
    protected function assertValidRequest(CartRequest $request)
    {
        $storeId = session()->get(CURRENT_STORE);
        $store = Store::find($storeId);
        $product = Product::find($request->product);
        
        $product = $this->productRepository->getSingleProduct($store, $product->slug);
        
        if (count($product['colors']) > 0 && !$request->color) {
            throw AddToCartException::noSelectedColor();
        }

        if (count($product['sizes']) > 0 && !$request->size) {
            throw AddToCartException::noSelectedSize();
        }
    }

    /**
     * Delete product from cart
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function deleteProductFromCart(Request $request)
    {
        $request->validate([
            'product' => 'required'
        ]);

        try {
            $user = web_user();
            DB::transaction(function() use ($request, $user) {
                Cart::where('user_id', $user->id)
                    ->where('product_id', $request->product)
                    ->delete();
            });

            // TODO:: Return html of cart items
            return response()->json(['message' => 'Product has been removed from your cart!']);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Unable to delete this product from your cart.', 'failed', 'Failed', 400), 400);
        }
    }
}
