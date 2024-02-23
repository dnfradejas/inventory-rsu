<?php

namespace App\Repositories;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

class ProductRepository
{

    
    /**
     * Get all products
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->getQuery()->where(function($query){
            $query->whereNull('products.deleted_at');
        })->get();
    }

    /**
     * Query product
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        $builder = DB::table('products')
                    //  ->distinct('products.id')
                     ->select(
                         'products.id',
                         'products.product_name',
                         'products.sku',
                         'products.slug',
                         'products.price',
                         'products.discount_price',
                         'products.status',
                         'brands.id as brand_id',
                         'brands.brand',
                         'categories.id as category_id',
                         'categories.category',
                         'delivery_details.quantity as stock'
                     )
                     ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                     ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                     ->leftJoin('delivery_details', 'products.id', '=', 'delivery_details.product_id');
        return $builder;
    }
}