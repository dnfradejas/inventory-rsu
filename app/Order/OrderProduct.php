<?php

namespace App\Order;

class OrderProduct
{

    /**
     * @var array
     */
    protected $product = [];

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $productName;

    /**
     * @var string
     */
    protected $productBrand;

    /**
     * @var string
     */
    protected $productCategory;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $size = 'N/A';

    /**
     * @var string
     */
    protected $color = 'N/A';

    /**
     * @var float
     */
    protected $total;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $unitOfMeasure;

    /**
     * @var int|float
     */
    protected $finalPrice;

    /**
     * @var int|float
     */
    protected $discountPrice = 0;

    /**
     * @var int|null
     */
    protected $sizeId = null;

    /**
     * @var int|null
     */
    protected $colorId = null;

    /**
     * @var int
     */
    protected $storeId;


    public function __construct(array $product)
    {
        $this->map($product);
        $this->product = $product;
    }

    protected function map(array $product)
    {
        foreach($product as $field => $p){
            if ($field != 'id') {
                $method = 'set' . camelize($field);
                $this->{$method}($p);
            }
        }
    }

    /**
     * Get store id
     *
     * @param integer $storeId
     * 
     * @return $this
     */
    public function setStoreId(int $storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * 
     * @return $this
     */
    protected function setSku(string $sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * 
     * @return $this
     */
    protected function setSlug(string $slug)
    {
        $this->slug = $slug;
        return $this;
    }
    
    /**
     * Set size id
     *
     * @param string|null $sizeId
     * 
     * @return $this
     */
    protected function setSizeId(string $sizeId = null)
    {
        $this->sizeId = $sizeId;
        return $this;
    }

    /**
     * Set color id
     *
     * @param string|null $colorId
     * 
     * @return $this
     */
    protected function setColorId(string $colorId = null)
    {
        $this->colorId = $colorId;
        return $this;
    }

    /**
     * Set unit of measure
     *
     * @param string $unitOfMeasure
     * 
     * @return $this
     */
    protected function setUnitOfMeasure(string $unitOfMeasure)
    {
        $this->unitOfMeasure = $unitOfMeasure;
        return $this;
    }

    /**
     * Set discount price
     * 
     * @param int|float $discountPrice
     *
     * @return $this
     */
    protected function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;
        return $this;
    }

    /**
     * Set final price
     *
     * @param int|float $finalPrice
     * 
     * @return $this
     */
    protected function setFinalPrice($finalPrice)
    {
        $this->finalPrice = $finalPrice;
        return $this;
    }

    /**
     * Set product id
     *
     * @param integer $id
     * 
     * @return $this
     */
    protected function setProductId(int $id)
    {
        $this->productId = $id;
        return $this;
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     * 
     * @return $this
     */
    protected function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Set product name
     * 
     * @param string $productName
     *
     * @return $this
     */
    protected function setProductName(string $productName)
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * Set product brand
     *
     * @param string $productBrand
     * 
     * @return $this
     */
    protected function setProductBrand(string $productBrand)
    {
        $this->productBrand = $productBrand;
        return $this;
    }

    /**
     * Set product category
     * 
     * @param string $productCategory
     *
     * @return $this
     */
    protected function setProductCategory(string $productCategory)
    {
        $this->productCategory = $productCategory;
        return $this;
    }

    /**
     * Set product price
     * 
     * @param int|float $price
     *
     * @return $this
     */
    protected function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Set product image
     *
     * @param string $image
     * 
     * @return $this
     */
    protected function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Set product size
     * 
     * @param null|string $size
     *
     * @return $this
     */
    protected function setSize(string $size = null)
    {
        $this->size = is_null($size) ? 'N/A' : $size;
        return $this;
    }

    /**
     * Set product color
     * 
     * @param null|string $color
     *
     * @return $this
     */
    protected function setColor(string $color = null)
    {
        $this->color = is_null($color) ? 'N/A' : $color;
        return $this;
    }

    /**
     * Set total
     *
     * @param int|float $total
     * 
     * @return $this
     */
    protected function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Get product id
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName()
    {
        return $this->productName;
    }

    /**
     * Get product brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->productBrand;
    }

    /**
     * Get product category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->productCategory;
    }

    /**
     * Get product price
     *
     * @return int|float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get discount price
     *
     * @return int|float
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * Get final price
     *
     * @return int|float
     */
    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

    /**
     * Get unit of measure
     *
     * @return $this
     */
    public function getUnitOfMeasure()
    {
        return $this->unitOfMeasure;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get product quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get product image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get product size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get product color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Get store id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Get total
     *
     * @return int|float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Convert data to array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->product;
    }
}