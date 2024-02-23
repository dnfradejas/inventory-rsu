<?php

namespace App\Http\Controllers\Cashier;

use stdClass;
use Exception;
use App\Models\Store;
use App\Models\Order;
use App\Models\Product;
use App\Models\ForOrder;
use Illuminate\Http\Request;
use App\Order\CreateOrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\ForOrderRepository;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Requests\Cashier\CashierRequest;
use App\Exceptions\ProductInventoryException;

class OrderController extends Controller
{
    

    /**
     * Display product information in modal
     * when Order button is clicked
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddQuantityModal(Request $request)
    {        

        $forOrder = ForOrder::where('product_id', $request->id)
                            ->where('store_id', $request->store_id)
                            ->first();

        $currentQuantity = $forOrder ? (int) $forOrder->quantity : 0;

        // $html = view('cashier.snippet.modal-quantity-input', $view_data)->render();
        
        return response()->json(['currentQuantity' => $currentQuantity], 200);
    }


    /**
     * Add product order
     *
     * @param \App\Http\Requests\Cashier\CashierRequest $request
     * @param \App\Repositories\ForOrderRepository $forOrderRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postOrderAddProduct(CashierRequest $request, ForOrderRepository $forOrderRepository)
    {
        $inputQty = (int) $request->quantity;
        $requestSize = 0;
        $requestColor = 0;

        if ($request->has('code')) {
            $inputQty = $request->has('quantity') ? (int) $request->quantity : 1;
        }

        try {
            $this->productSizeHasSufficientInventory($request, $inputQty);
            $productId = $request->id;
            $productStore = DB::table('product_store')
                         ->where(function($query) use ($request, &$productId) {
                            if ($request->has('code')) { // barcode reader is used
                                $product = Product::where('code', $request->code)->first();
                                $query->where('product_id', $product->id);
                                $productId = $product->id;
                            } else {
                                $query->where('product_id', $request->id);
                            }
    
                            $query->where('store_id', $request->store);
                         })
                         ->first();
                         
            if ($productStore) {
                $inputQty = $this->getPreOrderExistingQuantity($request, $inputQty);
                $this->validateInventoryStore($productStore, $inputQty);
                DB::transaction(function() use ($request, $requestSize, $requestColor, $inputQty, $productId) {
                    ForOrder::updateOrCreate([
                        'product_id' => $productId,
                        'store_id' => $request->store,
                        'size_id' => $requestSize,
                        'color_id' => $requestColor
                    ], [
                        'product_id' => $productId,
                        'size_id' => $requestSize,
                        'color_id' => $requestColor,
                        'store_id' => $request->store,
                        'quantity' => $inputQty,
                    ]);
                });
                
                $html = $this->getOrderListView($forOrderRepository, $request->store);
    
                return response()->json(['html' => $html]);
            }
        } catch (ProductInventoryException $e) {
            
            return response()->json(api_response($e->getMessage(), 'failed', 'Failed', 403), 403);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Cannot order product!', 'failed', 'Failed', 400), 400);
        }
        return response()->json(api_response('Product cannot be found!', 'failed', 'Failed', 400), 400);
    }


    /**
     * Get existing quantity of pre order product for barcode added product
     *
     * @param @param \App\Http\Requests\Cashier\CashierRequest $request
     * @param integer $inputQty
     * 
     * @return int
     */
    protected function getPreOrderExistingQuantity($request, int $inputQty)
    {
        if ($request->has('code')) {
            $order = DB::table('for_orders')
                       ->join('products', 'for_orders.product_id', '=', 'products.id')
                       ->where('products.code', $request->code)
                       ->first();
            if ($order) {
                $inputQty = $order->quantity + $inputQty;
            }
        }

        return $inputQty;
    }
    /**
     * Validate inventory of a product to make sure
     * it can be ordered
     *
     * @param \stdClass $productStore
     * @param int $inputQty
     * 
     * @return void
     */
    protected function validateInventoryStore(stdClass $productStore, $inputQty)
    {
        if ($productStore->inventory_count < $inputQty) {
            throw ProductInventoryException::insufficientStock();
        }
    }

    /**
     * Product size stock validation
     *
     * @param \App\Http\Requests\Cashier\CashierRequest $request
     * 
     * @return void
     */
    protected function productSizeHasSufficientInventory(CashierRequest $request, $inputQty)
    {
        if ($request->has('size') && $request->size) {

            $size = DB::table('product_size')
                      ->where('size_id', $request->size)
                      ->first();
            if ($size->inventory_count < $inputQty) {
                throw ProductInventoryException::insufficientStock();
            }
        }
    }

    /**
     * Get order list item view
     *
     * @param \App\Repositories\ForOrderRepository $forOrderRepository
     * @param int $storeId
     * @return string
     */
    protected function getOrderListView(ForOrderRepository $forOrderRepository, int $storeId)
    {
        $forOrders = $forOrderRepository->get($storeId);
        $orders = $forOrders['orders'];
        $grandTotal = $forOrders['grandTotal'];
        
        $html = view('cashier.snippet.order-list-items', ['orders' => $orders, 'grandTotal' => number_format($grandTotal, 2)])->render();

        return $html;
    }

    /**
     * Delete for order item
     *
     * @param \App\Repositories\ForOrderRepository $forOrderRepository
     * @param integer $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteForOrderItem(ForOrderRepository $forOrderRepository, int $id)
    {
        try {
            $html = null;

            DB::transaction(function() use ($id, $forOrderRepository, &$html) {
                $fo = ForOrder::find($id);
                $fo->delete();
                
                $html = $this->getOrderListView($forOrderRepository, $fo->store_id);

                return $html;
            });

            return response()->json([
                'html' => $html
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());

        }
    }


    /**
     * Create order
     * 
     * @param \App\Http\Requests\Admin\OrderRequest $request
     * @param \App\Order\CreateOrderProduct $orderProduct
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(OrderRequest $request, CreateOrderProduct $orderProduct)
    {
        $store = Store::find($request->store);
        try {
            DB::transaction(function() use ($store, $orderProduct) {
                $orderProduct->setSoldTo('N/A')
                             ->setStatus(Order::ORDER_PROCESSING)
                             ->setStore($store)
                             ->process();
                return response()->json(api_response('Created'));
            });
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('System error. Cannot process order this time'), 400);

        }
    }
}