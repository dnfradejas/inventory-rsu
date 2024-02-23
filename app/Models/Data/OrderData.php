<?php

namespace App\Models\Data;

use App\Models\Store;
use App\Models\Data\BaseData;

class OrderData extends BaseData
{

    /**
     * @var string
     */
    const USER_ID = 'user_id';

    /**
     * @var string
     */
    const ORDER_REF = 'order_ref';

    /**
     * @var string
     */
    const SOLD_TO = 'sold_to';

    /**
     * @var string
     */
    const ORDER_FROM = 'order_from';

    /**
     * @var string
     */
    const STORE = 'store';

    /**
     * @var string
     */
    const STATUS = 'status';

    /**
     * @var string
     */
    const ORDER_PRODUCTS = 'order_products';

    /**
     * @var string
     */
    const GRAND_TOTAL = 'grand_total';

    /**
     * Set user id
     *
     * @param mixed $userId
     * 
     * @return $this
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * Set order reference
     *
     * @param string $orderRef
     * 
     * @return $this
     */
    public function setOrderReference(string $orderRef)
    {
        return $this->setData(self::ORDER_REF, $orderRef);
    }

    /**
     * Set sold to name
     * 
     * @param string $soldTo
     *
     * @return $this
     */
    public function setSetSoldTo(string $soldTo)
    {
        return $this->setData(self::SOLD_TO, $soldTo);
    }

    /**
     * Set order origin
     *
     * @param string $orderFrom
     * 
     * @return $this
     */
    public function setOrderFrom(string $orderFrom)
    {
        return $this->setData(self::ORDER_FROM, $orderFrom);
    }

    /**
     * Set store where the products are bought
     * 
     * @param \App\Models\Store $store
     *
     * @return $this
     */
    public function setStore(Store $store)
    {
        return $this->setData(self::STORE, $store);
    }

    /**
     * Set order status
     *
     * @param string $status
     * 
     * @return $this
     */
    public function setStatus(string $status)
    {
        return $this->setData(self::STATUS, $status);
    }


    /**
     * Set ordered products
     * 
     * @param \App\Order\OrderProduct[] $orderProducts
     *
     * @return $this
     */
    public function setOrderProducts(array $orderProducts)
    {
        return $this->setData(self::ORDER_PRODUCTS, $orderProducts);
    }

    /**
     * Set grand total
     *
     * @param float $grantTotal
     * 
     * @return $this
     */
    public function setGrandTotal(float $grantTotal)
    {
        return $this->setData(self::GRAND_TOTAL, $grantTotal);
    }

}