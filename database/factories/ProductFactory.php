<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_id' => 1,
            'category_id' => 1,
            'unit_of_measure_id' => 1,
            'product_name' => 'Product 1',
            'sku' => 'sku1',
            'slug' => 'product-1',
            'price' => 599,
            'discount_price' => 0,
            'image' => '/storage/app/products/t/e/testproduct.jpg',
            'status' => Product::ACTIVE,
        ];
    }
}
