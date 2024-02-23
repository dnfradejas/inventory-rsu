<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => 1,
            'supplier_id' => 1,
            'quantity' => 10,
            'barcode' => '123',
            'delivery_date' => '2022-02-01 13:40',
            'production_date' => now()->__toString(),
            'expiration_date' => now()->addDays(10)->__toString(),
        ];
    }
}
