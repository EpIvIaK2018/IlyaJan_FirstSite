<?php
namespace App\order;
require_once 'product.php';
class greenTea extends product
{
    public function __construct($weight)
    {
        $this->priceFor50 = 1.5;
        $this->weight = (double)$weight;
        $this->name = 'Зелёный чай';
    }
}