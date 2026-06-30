<?php

namespace App\Core\ShoppingCart\Contracts;

use App\Core\ShoppingCart\CartItem;

interface Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem);
}
