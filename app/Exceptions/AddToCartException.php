<?php

namespace App\Exceptions;

class AddToCartException extends \Exception
{

    public static function noSelectedSize()
    {
        return new static("Please select size.");
    }

    public static function noSelectedColor()
    {
        return new static("Please select color.");
    }
}