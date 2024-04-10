<?php

namespace App\Http\Controllers\Admin\Order;

use stdClass;
use Exception;
use App\Models\Order;
use ICanBoogie\Inflector;
use Crazymeeks\PHPExcel\Xls;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Order\OrderCreator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Export\Order\Order as OrderExporter;
use App\Models\DeliveryDetail;

class OrderController extends Controller
{
    
    /**
     * @var \App\Order\OrderCreator
     */
    protected $orderCreator;

    /**
     * @var \App\Repositories\OrderRepository
     */
    protected $orderRepository;

    public function __construct(OrderCreator $orderCreator, OrderRepository $orderRepository)
    {
        $this->orderCreator = $orderCreator;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Add product to be order
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddOrder(Request $request)
    {
        $builder = $this->getDeliveryDetailsQuery();
        
        if ($request->has('barcode') && $request->barcode) {
            $builder = $builder->where('delivery_details.barcode', $request->barcode);
        } elseif ($request->has('id') && $request->id) {
            $builder = $builder->where('delivery_details.id', $request->id);
        } elseif ($request->has('order_product_id') && $request->order_product_id) {
            $orderProduct = OrderProduct::find($request->order_product_id);
            $builder = $builder->where('delivery_details.id', $orderProduct->delivery_detail_id);
        }

        $product = $builder->first();

        if ($product) {
            try {
                $this->assertEnoughQuantity($product, $request);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 403);
            }
            $this->orderCreator->process($product, $request);
        }

        return $this->responseOrderList();
        
    }

    protected function assertEnoughQuantity(stdClass $product, Request $request)
    {
        if ($product->quantity < $request->quantity) {
            throw new Exception(sprintf("No stock available for product %s", $product->product_name));
        }
    }

    /**
     * Response list of orders wrapped in an html markups
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseOrderList()
    {
        $data = [
            'orders' => $this->orderRepository->draft(),
        ];

        $html = view('admin.pages.order.section.orderlist', $data)->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Delete order item
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OrderProduct $orderProduct
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeleteOrderItem(Request $request, OrderProduct $orderProduct)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = (int) $request->id;

        $orderProduct->find($id)->delete();

        return $this->responseOrderList();
    }

    /**
     * When clicking order now button, we will just update the status of order from Draft - ORDER_PROCESSING
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postOrderNow()
    {

        $orders = DB::table('orders')
            ->select(
                'orders.id as order_id',
                'order_products.*'
            )
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->where('orders.status', Order::ORDER_DRAFT)
            ->get();

        try {
            DB::transaction(function() use ($orders) {
                Order::whereStatus(Order::ORDER_DRAFT)->update([
                    'status' => Order::ORDER_PROCESSING,
                    'updated_at' => now()->__toString(),
                ]);

                $this->deductQuantityFromStocks($orders);
            });
            return $this->responseOrderList();
        } catch (Exception $e) {
            return response()->json(['message' => 'Cannot process order now :(', 'error' => $e->getMessage()], 400);
            
        }
        
    }

    /**
     * Deduct quantity from stocks
     *
     * @param \Illuminate\Support\Collection $orders
     * 
     * @return void
     */
    protected function deductQuantityFromStocks(Collection $orders)
    {
        foreach($orders as $order) {
            $detail = DeliveryDetail::find($order->delivery_detail_id);
            $detail->quantity = ($detail->quantity - $order->quantity);
            $detail->save();
        }
    }

    /**
     * Export orders
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postExportOrders(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'status' => 'required'
        ]);

        $exploded = explode(' - ', $request->date);
        $from = explode('/', $exploded[0]);
        $from = $from[2] . '-' . $from[0] . '-' . $from[1] . ' 00:00:00';

        $to = explode('/', $exploded[1]);
        $to = $to[2] . '-' . $to[0] . '-' . $to[1] . ' 23:59:59';

        $orders = DB::table('orders')
                    ->select(
                        'orders.*',
                        'order_products.product_name',
                        'order_products.product_brand',
                        'order_products.product_category',
                        'order_products.sku',
                        'order_products.final_price as price',
                        'order_products.quantity',
                        'order_products.color',
                        'order_products.size',
                        'order_products.uom'
                    )
                    ->join('order_products', 'orders.id', '=', 'order_products.order_id')
                    ->where('orders.status', $request->status)
                    ->whereBetween('orders.created_at', [$from, $to])
                    ->cursor();

        $data = [];
        foreach($orders as $order){
            $data[] = [
                $order->order_ref,
                $order->sold_to,
                'PHP' ,
                $order->store_name,
                $order->product_name,
                $order->product_brand,
                $order->product_category,
                $order->sku,
                'PHP' . $order->price,
                $order->quantity,
                $order->uom,
                $order->color,
                $order->size,
            ];
        }

        $dataProvider = new OrderExporter($data);
        if (config('app.env') === 'testing') {
            return response()->json($data);
        }
        
        if (count($data) > 0) {
            $exporter = new Xls();
            return $exporter->download($dataProvider);
        }

        $from = date_to_human($from, "M j, Y");
        $to = date_to_human($to, "M j, Y");
        
        return redirect()->back()->with('message', sprintf("No report found from %s to %s", $from, $to));

    }

    /**
     * Display store selection page for creating new order
     *
     * @return \Illuminate\View\View
     */
    public function newOrder()
    {
        $details = $this->getDeliveryDetails();
        
        return view('admin.pages.order.new-order', [
            'details' => $details,
            'orders' => $this->orderRepository->draft(),
        ]);
    }


    

    /**
     * Get list of orders
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\View\View
     */
    public function list(Request $request)
    {

        $orders = Order::whereNotIn('status', [Order::ORDER_DRAFT])->get();

        if (config('app.env') === 'testing') {
            return response()->json(api_response($orders));
        }

        return view('admin.pages.order.listing', ['orders' => $orders]);
    }

    

    /**
     * View order detail
     *
     * @param string $order_ref
     * 
     * @return \Illuminate\View\View
     */
    public function viewOrderDetail(string $order_ref)
    {
        $orderDetails = DB::table('orders')
                          ->select(
                              'orders.id as order_id',
                              'orders.sold_to',
                              'orders.order_from',
                              'orders.status',
                              'orders.order_ref',
                              'order_products.*'
                          )
                          ->join('order_products', 'orders.id', '=', 'order_products.order_id')
                          ->where('orders.order_ref', $order_ref)
                          ->get();
        
        $o = [];
        $orderInfo = new stdClass();
        $orderInfo->name = null;
        
        
        foreach($orderDetails as $order){

            if (is_null($orderInfo->name)) {
                $orderInfo->id = $order->order_id;
                $orderInfo->name = $order->sold_to;
                $orderInfo->order_from = $order->order_from;
                $orderInfo->status = $order->status;
                $orderInfo->order_ref = $order->order_ref;
                $orderInfo->created_at = date("M j, Y g:i a", strtotime($order->created_at));
            }

            $rowTotal = $order->final_price * $order->quantity;

            $obj = new stdClass();
            $obj->product_name = $order->product_name;
            $obj->product_brand = $order->product_brand;
            $obj->product_category = $order->product_category;
            $obj->order_ref = $order->order_ref;
            $obj->quantity = $order->quantity;
            $obj->price = $order->final_price;
            
            $obj->sku = $order->sku;
            
            $obj->image = $order->image;
            $obj->created_at = date("M j, Y, g:i a", strtotime($order->created_at));

            $obj->row_total = $rowTotal;
            $obj->string_row_total = number_format($rowTotal, 2);

            $o[] = $obj;
            unset($order, $obj);
        }
        
        if (config('app.env') === 'testing') {
            return response()->json(api_response([
                'order' => $o,
                'orderInfo' => $orderInfo
            ]));
        }

        return view('admin.pages.order.details', [
            'products' => $o,
            'orderInfo' => $orderInfo,
            'cardTitle' => 'Order Ref #: ' . $orderInfo->order_ref,
        ]);
        
    }

    /**
     * Get statuses
     *
     * @param integer $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatuses(int $id)
    {
        $order = Order::find($id);
        
        $statuses = $order->getAllStatus();
        if (($key = array_search($order->status, $statuses)) !== false) {
            unset($statuses[$key]);
        }

        $html = view('admin.pages.order.update-status-modal', ['statuses' => $statuses])->render();
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Cancel order
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCancel(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        
        try {
            $order = Order::find($request->id);
            
            $callback = function($orderProduct){
                $detail = DeliveryDetail::find($orderProduct->delivery_detail_id);
                
                $detail->quantity = $detail->quantity + $orderProduct->quantity;
                
                $detail->save();
            };

            if (!in_array($order->status, [Order::ORDER_PAID, Order::ORDER_CANCELLED]) ) {
                DB::transaction(function() use ($order, $callback) {
                    $order->update([
                        'status' => Order::ORDER_CANCELLED,
                        'updated_at' => now()->__toString(),
                    ]);

                    $orderProducts = OrderProduct::whereOrderId($order->id)->get();
                    foreach($orderProducts as $orderProduct){
                        $callback($orderProduct);
                    }
                    
                });
                return response()->json(api_response('Order has been cancelled.'));
            }

            return response()->json(api_response('Order cannot be cancelled.', 'error', 'Error', 400), 400);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while cancelling order. Please try again!'), 400);
        }
    }

    /**
     * Update order status
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $order = Order::find($request->id);
            
        $callback = function($orderProduct){
            $detail = DeliveryDetail::find($orderProduct->delivery_detail_id);
            
            $detail->quantity = $detail->quantity + $orderProduct->quantity;
            
            $detail->save();
        };

        try {
            
            DB::transaction(function() use ($request, $order, $callback) {
                $order->update([
                    'status' => $request->status,
                    'updated_at' => now()->__toString(),
                ]);
                if ($request->status == Order::ORDER_CANCELLED) {
                    $orderProducts = OrderProduct::whereOrderId($order->id)->get();
                    foreach($orderProducts as $orderProduct){
                        $callback($orderProduct);
                    }
                }
            });
            return response()->json(api_response('Order status successfully updated!'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while updating order status!', 'failed', 'Failed', 400), 400);
        }
    }

    /**
     * Display receipt
     *
     * 
     * @param string $orderRef
     * 
     * @return \Illuminate\View\View
     */
    public function displayReceiptAsPdf(string $orderRef)
    {

        $inflector = Inflector::get('en');
        
        $orders = DB::table('orders')
                    ->select(
                        'orders.*',
                        'order_products.product_name',
                        'order_products.product_brand',
                        'order_products.product_category',
                        'order_products.sku',
                        'order_products.barcode',
                        'order_products.price',
                        'order_products.final_price',
                        'order_products.discount_price',
                        'order_products.quantity',
                        'order_products.total',
                        'order_products.uom',
                        'order_products.image'
                    )
                    ->join('order_products', 'orders.id', '=', 'order_products.order_id')
                    ->where('orders.status', Order::ORDER_PAID)
                    // ->where('orders.reference_number', $orderRef)
                    ->get();
        return view('admin.pages.order.receipt.template', ['orders' => $orders, 'inflector' => $inflector]);
    }
}
