<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'order_ref' => reference_number(),
            'sold_to' => 'N/A',
            'order_from' => Order::STORE_ORDER,
            'status' => Order::ORDER_PAID,
        ];
    }
}
