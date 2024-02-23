<?php

namespace App\Repositories;

use stdClass;
use Exception;
use App\Models\Store;
use App\Models\Product;
use App\Models\ForOrder;
use Illuminate\Support\Facades\DB;

class ForOrderRepository
{

    public function get(int $storeId): array
    {
        
        $orders = ForOrder::where('store_id', $storeId)->cursor();

        $o = [];
        $grandTotal = 0;
        
        foreach($orders as $order) {
            $productId = $order->product_id;
            
            $product = Product::find($productId);
            $productSize = null;
            
            $size = null;
            if ($product) {
                $productSize = DB::table('product_size')
                                 ->select(
                                     'product_size.*',
                                     'sizes.size'
                                 )
                                 ->join('sizes', 'product_size.size_id', '=', 'sizes.id')
                                 ->where(function($query) use ($order, $product) {
                                    if ($order->size_id != 0) {
                                        $query->where('product_size.size_id', $order->size_id);
                                    }
                                    return $query->where('product_size.product_id', $product->id);
                                 })
                                 ->first();

                $price = $product->discount_price && $product->discount_price > 0 ? $product->discount_price : $product->price;
                if ($productSize) {
                    $size = $productSize->size;
                    if ($productSize->discount_price > 0) {
                        $price = $productSize->discount_price;
                    } else {
                        $price = $productSize->price;
                    }
                }
    
                $rowTotal = ($price * $order->quantity);
    
                $obj = new stdClass();
                $obj->id = $order->id;
                $obj->store_id = $order->store_id;
                $obj->product_name = $product->product_name;
                $obj->price = number_format($price, 2);
                $obj->quantity = $order->quantity;
                $obj->rowTotal = number_format($rowTotal, 2);
                $obj->sku = $product->sku;
                $obj->size = $size;
                $o[] = $obj;
    
                $grandTotal += $rowTotal;
            }
            unset($order);
        }

        return [
            'orders' => $o,
            'grandTotal' => $grandTotal
        ];
    }
}