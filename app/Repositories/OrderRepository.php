<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository
{

    /**
     * Get draft orders
     *
     * @return \Illuminate\Support\Collection
     */
    public function draft()
    {
        $results = $this->query()
                        ->where('orders.status', Order::ORDER_DRAFT)
                        ->get();
        return $results;
    }

    /**
     * Get order query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query()
    {
        $builder = DB::table('orders')
                     ->select(
                        'orders.id as order_id',
                        'orders.order_ref',
                        'orders.status',
                        'order_products.*'
                     )
                     ->join('order_products', 'orders.id', '=', 'order_products.order_id');
        return $builder;
    }
}