<?php

namespace App\Exceptions;

use Exception;

class ProductInventoryException extends Exception
{


    public static function insufficientStock(string $message = "Product has insufficient stock")
    {
        return new static($message);
    }
}