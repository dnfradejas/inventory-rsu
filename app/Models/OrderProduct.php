<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'order_id',
        'delivery_detail_id',
        'product_name',
        'product_brand',
        'product_category',
        'sku',
        'barcode',
        'price',
        'discount_price',
        'final_price',
        'quantity',
        'uom',
        'image',
        'total',
    ];


    /**
     * Get order of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
