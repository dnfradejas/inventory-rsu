<?php

namespace App\Models\Repositories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Data\BaseData;
use App\Models\Data\OrderData;
use Illuminate\Support\Facades\DB;

class OrderRepository
{

    /** @var array */
    const WEEK_NAMES = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 7,
    ];

    /** @var array */
    private $weeklySales = [
        'Monday' => 0,
        'Tuesday' => 0,
        'Wednesday' => 0,
        'Thursday' => 0,
        'Friday' => 0,
        'Saturday' => 0,
        'Sunday' => 0,
    ];

    /**
     * @var \App\Models\Order
     */
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get orders
     * 
     * @return array
     */
    public function orders()
    {
        $orders = $this->order->with(['order_products'])->paid()->get();

        $ordersCount = count($orders);
        $totalSales = 0;
        $orderIds = [];
        foreach($orders as $order){
            foreach($order->order_products as $product){
                $totalSales += $product->total;

            }
            array_push($orderIds, $order->id);
        }

        $topSellingProducts = $this->getTopSellingProducts($orderIds);
        $companySales = $this->getCompanySales($topSellingProducts);
        // $weeklySales = $this->getWeeklySales();
        return [$ordersCount, $totalSales, $topSellingProducts, $companySales];
    }

    protected function getCompanySales(array $topSellingProducts)
    {
        $data = [];
        foreach($topSellingProducts as $tp){
            $data[$tp['product_name']] = $tp['volume'];
        }

        return $data;
    }

    /**
     * Get weekly sales
     * 
     * @return array
     */
    protected function getWeeklySales(): array
    {

        $getCurrentWeekName = get_current_weekname();

        $currentWeekName = $getCurrentWeekName(now());

        $weekNameInt = self::WEEK_NAMES[$currentWeekName];

        list($startDate, $endDate) = $this->getStartDateAndEndDate($weekNameInt);

        $weeklyOrders = $this->getOrderBetween($startDate, $endDate);

        foreach($weeklyOrders as $order){
            $weekName = $getCurrentWeekName($order->created_at);    
            $this->weeklySales[$weekName] += $order->grand_total;
        }

        return $this->weeklySales;
    }

    private function getStartDateAndEndDate(int $weekNameInt)
    {
        $startDate = now()->format('Y-m-d 00:00:00');
        $endDate = now()->format('Y-m-d 23:59:59');
        
        if ($weekNameInt > 1) {
            $weekNameInt -= 1;
            $startDate = now()->subDays($weekNameInt)->format('Y-m-d 00:00:00');
            $endDate = now()->format('Y-m-d 23:59:59');
        }
        return [$startDate, $endDate];
    }

    /**
     * Get paid orders between two dates
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrderBetween($startDate, $endDate)
    {
        $weeklyOrders = $this->order
                             ->paid()
                             ->whereBetween('created_at', [$startDate, $endDate])
                             ->orderBy('id', 'ASC')
                             ->get();
        
        return $weeklyOrders;
    }

    /**
     * Get top selling products
     * 
     * @param array $orderIds
     * 
     * @return array
     */
    protected function getTopSellingProducts(array $orderIds): array
    {
        $orderProducts = $this->getProductsByOrderId($orderIds);
        
        $topSellingProducts = [];
        $attribute = function($product){
            return [
                'product_name' => $product->product_name,
                'sku' => $product->sku,
                'quantity' => $product->quantity,
                'price' => $product->final_price,
            ];
        };

        foreach($orderProducts as $product){
            $mergedProduct = array_merge(['volume' => 1], $attribute($product));
            if (isset($topSellingProducts[$product->sku])) {
                $volume = $topSellingProducts[$product->sku]['volume'] + 1;
                
                $topSellingProducts[$product->sku]['volume'] = $volume;
                
            } else {
                $topSellingProducts[$product->sku] = $mergedProduct;
            }
        }

        if (count($topSellingProducts) > 0) {
            /** Sort array by highest volume */
            usort($topSellingProducts, function($a, $b) {
                return $a['volume'] <=> $b['volume'];
            });
        }
        
        return $topSellingProducts;
    }

    /**
     * Get order products based on order id
     * 
     * @param int|array $orderId
     * 
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getProductsByOrderId($orderId)
    {
        $orderId = (array) $orderId;
        $orderId = array_filter($orderId);

        if (count($orderId) > 0) {
            $orderProducts = OrderProduct::whereIn('order_id', $orderId)->get();
    
            return $orderProducts;
        }

        return [];
    }

    /**
     * Create order
     *
     * @param \App\MOdels\Data\BaseData $orderData
     * 
     * @return \App\Models\Data\BaseData
     * 
     * @throws \Exception
     */
    public function create(BaseData $orderData)
    {
        /** @var \App\Models\Store $store */
        $store = $orderData->getData(OrderData::STORE);
        $userId = $orderData->getData(OrderData::USER_ID);
        DB::transaction(function() use ($orderData, $store, $userId) {
            $order = Order::create([
                'user_id' => $userId,
                'order_ref' => $orderData->getData(OrderData::ORDER_REF),
                'sold_to' => $orderData->getData(OrderData::SOLD_TO),
                'order_from' => $orderData->getData(OrderData::ORDER_FROM),
                'store_name' => $store->store_name,
                'store_address' => $store->address,
                'store_tin' => $store->tin,
                'status' => $orderData->getData(OrderData::STATUS),
                'grand_total' => $orderData->getData(OrderData::GRAND_TOTAL),
            ]);

            $this->createOrderProduct($order, $orderData);
        });

        // reset customer cart
        Cart::where('user_id', $userId)
            ->where('store_id', $store->id)
            ->delete();
    }

    /**
     * Create order products
     *
     * @param \App\Models\Order $order
     * @param \App\Models\Data\OrderData $orderData
     * 
     * @return void
     */
    protected function createOrderProduct(Order $order, OrderData $orderData)
    {
        $orderedProducts = $orderData->getData(OrderData::ORDER_PRODUCTS);
        $products = [];
        foreach($orderedProducts as $op){
            
            $products[] = [
                'order_id' => $order['id'],
                'product_name' => $op['product_name'],
                'product_brand' => $op['product_brand'],
                'product_category' => $op['product_category'],
                'sku' => $op['sku'], //
                'code' => $op['code'], //
                'price' => $op['price'],
                'discount_price' => $op['discount_price'], //
                'final_price' => $op['final_price'], //
                'quantity' => $op['quantity'],
                'color' => $op['color'] ? $op['color'] : 'N/A',
                'size' => $op['size'] ? $op['size'] : 'N/A',
                'uom' => $op['unit_of_measure'], //
                'image' => $op['image'],
                'total' => $op['total'],
            ];
        }
        
        OrderProduct::insert($products);
    }
}