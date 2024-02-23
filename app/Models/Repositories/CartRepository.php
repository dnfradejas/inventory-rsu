<?php

namespace App\Models\Repositories;

use stdClass;
use App\Order\OrderProduct;
use Illuminate\Support\Facades\DB;

class CartRepository
{


    /**
     * Get cart items
     * 
     * @param string $userId
     * @param int $storeId
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $userId, int $storeId)
    {
        $shoppingCart = DB::table('cart')
                          ->select(
                              'cart.id',
                              'cart.quantity',
                              'cart.store_id',
                              'products.id as product_id',
                              'products.product_name',
                              'products.price',
                              'products.discount_price',
                              'products.image',
                              'products.sku',
                              'products.code',
                              'products.slug',
                              'sizes.id as size_id',
                              'sizes.size',
                              'colors.id as color_id',
                              'colors.color',
                              'brands.brand',
                              'categories.category',
                              'unit_of_measures.unit as unit_of_measure'
                          )
                          ->leftJoin('products', 'cart.product_id', '=', 'products.id')
                          ->leftJoin('sizes', 'cart.size_id', '=', 'sizes.id')
                          ->leftJoin('colors', 'cart.color_id', '=', 'colors.id')
                          ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                          ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                          ->leftJoin('unit_of_measures', 'products.unit_of_measure_id', '=', 'unit_of_measures.id')
                          ->where('cart.user_id', $userId)
                          ->where('cart.store_id', $storeId)
                          ->get();

        $data = [
            'products' => []
        ];
        $grandTotal = 0;
        $totalQuantity = 0;
        foreach($shoppingCart as $cart){
            $price = $cart->discount_price > 0 ? $cart->discount_price : $cart->price;
            $total = $cart->quantity * $price;
            $grandTotal += $total;
            $totalQuantity += $cart->quantity;
            $data['products'][] = $this->createOrderProductObject($cart, $price, $total);
        }
        $data['total_quantity'] = $totalQuantity;
        $data['grand_total'] = $grandTotal;

        return $data;
    }


    /**
     * Create order product object
     *
     * @param \stdClass $cart
     * @param int|float $price
     * @param int|float $total
     * 
     * @return array
     */
    protected function createOrderProductObject(stdClass $cart, $price, $total)
    {
        return [
            'id' => $cart->id,
            'quantity' => $cart->quantity,
            'store_id' => $cart->store_id,
            'product_id' => $cart->product_id,
            'product_name' => $cart->product_name,
            'product_brand' => $cart->brand,
            'slug' => $cart->slug,
            'product_category' => $cart->category,
            'sku' => $cart->sku,
            'code' => $cart->code,
            'unit_of_measure' => $cart->unit_of_measure,
            'price' => $cart->price,
            'final_price' => $price,
            'discount_price' => $cart->discount_price,
            'image' => $cart->image ? url($cart->image) : '',
            'size' => $cart->size,
            'size_id' => $cart->size_id,
            'color' => $cart->color,
            'color_id' => $cart->color_id,
            'total' => $total,
        ];
    }
}