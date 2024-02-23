<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    /**
     * Get delivery details
     *
     * @param string|integer|null $id
     * 
     * @return \Illuminate\Support\Collection|\stdClass
     * 
     * @todo move this to repository
     */
    protected function getDeliveryDetails($id =  null)
    {
        $details = $this->getDeliveryDetailsQuery()
                        ->where(function($query) use ($id) {
                        if ($id) {
                            $query->where('delivery_details.id', $id);
                        }
                        $query->where('products.status', Product::ACTIVE)
                              ->whereNull('delivery_details.deleted_at');
                      })
                      ->orderBy('delivery_details.expiration_date', 'asc');
        if ($id) {
            return $details->first();
        }

        return $details->get();
    }

    /**
     * Get delivery details builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getDeliveryDetailsQuery()
    {
      $details = DB::table('delivery_details')
                      ->select(
                        'delivery_details.*',
                        'products.id as product_id',
                        'products.product_name',
                        'products.price',
                        'products.discount_price',
                        'products.sku',
                        'products.image',
                        'suppliers.id as supplier_id',
                        'suppliers.name as supplier_name',
                        'unit_of_measures.unit as uom',
                        'brands.brand',
                        'categories.category'
                      )
                      ->join('suppliers', 'delivery_details.supplier_id', '=', 'suppliers.id')
                      ->join('products', 'delivery_details.product_id', '=', 'products.id')
                      ->join('unit_of_measures', 'products.unit_of_measure_id', '=', 'unit_of_measures.id')
                      ->join('brands', 'products.brand_id', '=', 'brands.id')
                      ->join('categories', 'products.category_id', '=', 'categories.id');
      return $details;
    }
}
