<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'delivery_details';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'barcode',
        'delivery_date',
        'production_date',
        'expiration_date',
        'deleted_at',
    ];
}
