<?php

namespace App\Order;

use stdClass;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order\Exception\ProductNotFoundException;

class OrderCreator
{

    protected $soldTo = 'N/A';
    protected $userId = null;
    protected $status = Order::ORDER_DRAFT;

    /**
     * Set id of the user(for user with accounts only)
     *
     * @param integer $id
     * 
     * @return $this
     */
    public function setUserId(int $id)
    {
        $this->userId = $id;
        return $this;
    }

    /**
     * Get id of the user
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * Set name of the buyer
     *
     * @param string $status
     * 
     * @return $this
     */
    public function setSoldTo(string $soldTo)
    {
        $this->soldTo = $soldTo;
        return $this;
    }

    /**
     * Set status of order
     *
     * @param string $status
     * 
     * @return $this
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }


    /**
     * Get name of the buyer
     *
     * @return string
     */
    public function getSoldTo()
    {
        return $this->soldTo;
    }

    /**
     * Get status of the order
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Process order
     *
     * @param \stdClass $product
     * @param \Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function process(stdClass $product, Request $request)
    {
        
        $order = Order::updateOrCreate([
            'status' => Order::ORDER_DRAFT
        ],[
            'user_id' => $this->getUserId(), // prepared for online store soon if ever
            'sold_to' => $this->getSoldTo(),
            'order_from' => Order::STORE_ORDER,
            'order_ref' => reference_number(),
            'status' => $this->getStatus(),
        ]);
        

        $finalPrice = $product->discount_price > 0 ? $product->discount_price : $product->price;
        $quantity = (int) $request->quantity;
        $filter = [
            'order_id' => $order->id,
            'barcode' => $request->has('barcode') && $request->barcode ? $request->barcode : null,
        ];

        
        if ($request->has('id') && $request->id) {
            $filter = [
                'order_id' => $order->id,
                'id' => $request->id
            ];
        }

        if ($request->has('delivery_detail_id')) {
            $filter = [
                'order_id' => $order->id,
                'delivery_detail_id' => $request->id
            ];
        }

        if ($request->has('order_product_id')) {
            $filter = [
                'order_id' => $order->id,
                'id' => $request->order_product_id
            ];
            
        }
        
        if ($request->has('quantity_append') && $request->quantity_append == 'true') {
            $p = OrderProduct::where(function($query) use ($request, $filter) {
                foreach($filter as $field  => $value){
                    $query->where("$field", $value);
                }
            })->first();
            
            if ($p) {
                $quantity += (int) $p->quantity;
            }
        }

        OrderProduct::updateOrCreate($filter, [
            'order_id' => $order->id,
            'delivery_detail_id' => $product->id,
            'product_name' => $product->product_name,
            'product_brand' => $product->brand,
            'product_category' => $product->category,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'price' => $product->price,
            'discount_price' => $product->discount_price,
            'final_price' => $finalPrice,
            'quantity' => $quantity,
            'uom' => $product->uom,
            'image' => null,
            'total' => ($finalPrice * $quantity),
        ]);

        
    }

    
}