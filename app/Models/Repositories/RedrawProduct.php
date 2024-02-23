<?php

namespace App\Models\Repositories;

trait RedrawProduct
{

    /**
     * Redraw product detail
     *
     * @param \Illuminate\Support\Collection $products
     * @param \Closure $callback
     * 
     * @return void
     */
    public function redrawProductDetail(\Illuminate\Support\Collection $products, \Closure $callback)
    {
        $productIds = [];

        foreach($products as $product){

            if (!in_array($product->id, $productIds)) {
                $sizes = [];
                $colors = [];
                $filteredSizes = [];
                $filteredColors = [];
            }

            if ($product->size_id && !in_array($product->size_id, $filteredSizes)) {
                $filteredSizes[] = $product->size_id;
                
                $sizes[] = [
                    $product->size_id,
                    $product->size,
                ];
            }

            if ($product->color_id && !in_array($product->color_id, $filteredColors)) {
                $filteredColors[] = $product->color_id;
                $colors[] = [
                    $product->color_id,
                    $product->color,
                ];
            }

            $productIds[] = $product->id;
            $productIds = array_unique($productIds);
            $callback([
                'id' => $product->id,
                'product_name' => $product->product_name,
                'sku' => $product->sku,
                'code' => $product->code,
                'status' => $product->status,
                'brand' => $product->brand,
                'category' => $product->category,
                'price' => number_format($product->price, 2),
                'discount_price' => $product->discount_price ? number_format($product->discount_price, 2) : 0.00,
                'unit' => $product->unit,
                'image' => $product->image ? url($product->image) : url('/storage/images/default-image.png'),
                'slug' => $product->slug,
                'sizes' => $sizes,
                'colors' => $colors
            ]);
        }
    }
}