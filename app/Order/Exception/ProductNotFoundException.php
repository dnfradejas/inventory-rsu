<?php

namespace App\Order\Exception;

use Exception;

class ProductNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct("Product not found!");
    }
}