<?php

namespace App\Models\Repositories;


use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Repositories\RedrawProduct;

class ProductRepository
{


    use RedrawProduct;
    
    /**
     * @var \App\Models\Store
     */
    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Get detail of a single product
     *
     * @param \App\Models\Store $store
     * @param string $productSlug
     * 
     * @return array
     */
    public function getSingleProduct(Store $store, string $productSlug)
    {
        $products = $this->getProductQuery($store)
                     ->where('products.slug', $productSlug)
                     ->get();
        
        $data = [];

        $this->redrawProductDetail($products, function(array $product) use (&$data) {
            $data = $product;
        });
        $product = $data;
        unset($data);
        return $product;
    }

    

    /**
     * Get product query
     *
     * @param int|\App\Models\Store $store
     * @param array $options
     * 
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getProductQuery($store, array $options = [])
    {
        $store = $store instanceof Store ? $store->id : $store;

        return DB::table('products')
                     ->select(
                        'products.*',
                        'brands.brand',
                        'categories.category',
                        'sizes.id as size_id',
                        'sizes.size',
                        'colors.id as color_id',
                        'colors.color',
                        'unit_of_measures.unit'
                     )
                     ->leftJoin('product_size', 'products.id', '=', 'product_size.product_id')
                     ->leftJoin('color_product', 'products.id', '=', 'color_product.product_id')
                     ->leftJoin('sizes', 'product_size.size_id', '=', 'sizes.id')
                     ->leftJoin('colors', 'color_product.color_id', '=', 'colors.id')
                     ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                     ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                     ->leftJoin('unit_of_measures', 'products.unit_of_measure_id', '=', 'unit_of_measures.id')
                     ->leftJoin('product_store', 'products.id', '=', 'product_store.product_id')
                     ->where(function($query) use ($store, $options) {
                        $query->where('product_store.store_id', $store)
                              ->where('products.status', Product::ACTIVE)
                              ->whereNull('products.deleted_at');

                        if (isset($options['q'])) { // has search
                            $search = $options['q'];
                            $query->where('products.product_name', 'LIKE', '%' . $search . '%')
                                  ->orWhere('products.sku', 'LIKE', '%' . $search . '%')
                                  ->orWhere('products.code', 'LIKE', '%' . $search . '%')
                                  ->orWhere('products.price', 'LIKE', '%' . $search . '%');
                        }
                     });
    }


    /**
     * Retrieve products associated with the store
     * 
     * @param int $storeId
     * 
     * @param array $options
     *
     * @return array
     */
    public function retrieveStoreProducts(int $storeId, array $options = [])
    {
        $products = $this->getProductQuery($storeId, $options)->get();

        $data = [];

        $this->redrawProductDetail($products, function(array $product) use (&$data) {
            $data[$product['id']] = $product;
            
        });

        $products = $data;
        unset($data);
        return array_values($products);
    }
}