<?php
namespace App\order;
require_once 'product.php';
class lastTea extends product
{
    public function __construct($weight)
    {
        $this->priceFor50 = 3.5;
        $this->weight = (double)$weight;
        $this->name = 'Последний чай';
    }



}
