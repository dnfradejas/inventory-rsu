<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForOrder extends Model
{
    use HasFactory;

    protected $table = 'for_orders';

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'store_id',
        'quantity'
    ];
}
