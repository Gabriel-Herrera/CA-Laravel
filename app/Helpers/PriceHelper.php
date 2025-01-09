<?php

namespace App\Helpers;

class PriceHelper
{
    public static function formatPrice($price)
    {
        return '$ ' . number_format($price, 0, ',', '.') . ' CLP';
    }
}