<?php

namespace App\order;

class productsSummary
{
    private static array $productsSummary;

    public static function addProduct(product $product): void
    {
        self::$productsSummary[] = $product;
    }

    public static function getSumALl(): array{
        return self::$productsSummary;
    }
}