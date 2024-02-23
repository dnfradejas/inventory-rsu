<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    /**
     * @var string
     */
    const STORE_ORDER = 'store';

    /**
     * @var string
     */
    const WEB_ORDER = 'website';

    /**
     * @var string
     */
    const MOBILE_APP_ORDER = 'mobile_app';

    /**
     * @var string
     */
    const ORDER_PAID = 'Paid';

    /**
     * @var string
     */
    const ORDER_UNPAID = 'Unpaid';

    /**
     * @var string
     */
    const ORDER_CANCELLED = 'Cancelled';

    /**
     * @var string
     */
    const ORDER_PROCESSING = 'Processing';

    /**
     * @var string
     */
    const ORDER_DRAFT = 'Draft';

    protected $fillable = [
        'user_id',
        'order_ref',
        'sold_to',
        'order_from',
        'status'
    ];



    /**
     * Scope a query to only include paid orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::ORDER_PAID);
    }

    /**
     * Get products of this order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * Get user of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all status
     *
     * @return array
     */
    public function getAllStatus()
    {
        return [
            self::ORDER_PAID,
            self::ORDER_PROCESSING,
            self::ORDER_UNPAID,
            self::ORDER_CANCELLED,
        ];
    }
}
