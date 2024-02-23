<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => 1,
            'delivery_detail_id' => 1,
            'product_name' => 'Product1',
            'product_brand' => 'Brand1',
            'product_category' => 'Category1',
            'sku' => 'sku1',
            'barcode' => '123',
            'price' => 500,
            'discount_price' => 0,
            'final_price' => 500,
            'quantity' => 2,
            'total' => 1000,
            'image' => null,
            'uom' => 'pcs',
        ];
    }
}
