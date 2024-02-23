<?php

namespace App\Http\Controllers\Web;

use Exception;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Data\OrderData;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CheckoutRequest;
use App\Models\Repositories\CartRepository;
use App\Models\Repositories\OrderRepository;

class CheckoutController extends Controller
{

    /**
     * @var \App\Models\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * @var \App\Models\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \App\Models\Data\OrderData
     */
    protected $orderData;

    public function __construct(CartRepository $cartRepository, OrderRepository $orderRepository, OrderData $orderData)
    {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->orderData = $orderData;
    }

    /**
     * Display index page
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $shoppingCart = $this->cartRepository->get();
        
        if (is_testing()) {
            return response()->json($shoppingCart);
        }
    }

    /**
     * Checkout
     * 
     * @param \App\Http\Requests\Web\CheckoutRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCheckout(CheckoutRequest $request)
    {

        try {
            $this->orderRepository->create($this->createData($request));
            return response()->json(['message' => 'Order has been placed.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'We apologize for we unable to process order at the moment.'], 500);
        }

    }

    /**
     * Create order data
     *
     * @param \App\Http\Requests\Web\CheckoutRequest $request
     * 
     * @return \App\Models\Data\OrderData
     */
    protected function createData(CheckoutRequest $request)
    {
        $store = Store::find(session()->get(CURRENT_STORE));
        $shoppingCart = $this->cartRepository->get();
        
        $this->orderData
             ->setUserId(web_user()->id)
             ->setOrderReference(reference_number())
             ->setSetSoldTo($request->firstname . ' ' . $request->lastname)
             ->setOrderFrom(Order::WEB_ORDER)
             ->setStore($store)
             ->setStatus(Order::ORDER_PROCESSING)
             ->setOrderProducts($shoppingCart['products'])
             ->setGrandTotal($shoppingCart['grand_total']);

        return $this->orderData;
    }
}
