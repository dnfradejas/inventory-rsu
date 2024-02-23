<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Cart;
use App\Models\Store;
use App\Models\Product;
use App\Jwt\WhosThisToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CartRequest;
use App\Exceptions\AddToCartException;
use App\Models\Repositories\CartRepository;
use App\Models\Repositories\ProductRepository;

class CartController extends Controller
{
    
    /**
     * @var \App\Models\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \App\Models\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * @var \App\Jwt\WhosThisToken
     */
    protected $token;

    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        WhosThisToken $token)
    {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->token = $token;
    }


    /**
     * Get items from cart
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems(Request $request)
    {
        $request->validate([
            'store' => 'required'
        ]);
        $user = $this->token->getIdentity();

        $cartItems = $this->cartRepository->get($user->id, $request->store);
        
        return response()->json($cartItems);

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
        $user = $this->token->getIdentity();
        
        try {

            $this->assertValidRequest($request);
            
            DB::transaction(function() use ($request, $user) {
                $storeId = $request->store;
                $findCart = Cart::where(function($query) use ($request, $user, $storeId) {
                                    $query->where('product_id', $request->product)
                                          ->where('user_id', $user->id)
                                          ->where('store_id', $storeId);
                                    if ($request->has('color') && $request->color) {
                                        $query->where('color_id', $request->color);
                                    }

                                    if ($request->has('size') && $request->size) {
                                        $query->where('size_id', $request->size);
                                    }
                                 });
                $quantity = $request->has('quantity') && $request->quantity ? $request->quantity : 1;
                
                $data = [
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'product_id' => $request->product,
                    'size_id' => $request->has('size') && $request->size ? $request->size : null,
                    'color_id' => $request->has('color') && $request->color ? $request->color : null,
                    'quantity' => $quantity,
                ];
                
                if ($findCart->count() > 0) {
                    $cart = $findCart->first();
                    $quantity = $cart->quantity + 1;
                    $data['quantity'] = $request->has('quantity') && $request->quantity ? $request->quantity : $quantity;
                    $findCart->update($data);
                } else {
                    Cart::create($data);
                }
            });
            return $this->getItems($request);
            
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
        
        $store = Store::find($request->store);
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
            'id' => 'required',
            'store' => 'required'
        ]);

        $user = $this->token->getIdentity();
        
        try {
            
            DB::transaction(function() use ($request, $user) {
                Cart::where('user_id', $user->id)
                    ->where('id', $request->id)
                    ->delete();
            });
            return $this->getItems($request);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Unable to delete this product from your cart.', 'failed', 'Failed', 400), 400);
        }
    }
}
