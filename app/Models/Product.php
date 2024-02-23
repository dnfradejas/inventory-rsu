<?php

namespace App\Models;

use App\Models\Color;
use App\Models\Size;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Category;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    const ACTIVE = 'active';

    /**
     * @var string
     */
    const INACTIVE = 'inactive';

    protected $fillable = [
        'brand_id',
        'category_id',
        'unit_of_measure_id',
        'product_name',
        'description',
        'sku',
        'slug',
        'price',
        'discount_price',
        'image',
        'status',
        'deleted_at',
    ];


    /**
     * Get unit of measure of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id', 'id');
    }

    /**
     * Get brand of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get category of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get sizes of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    /**
     * Get colors of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

}
