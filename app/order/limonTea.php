<?php
namespace App\order;
require_once 'product.php';
class limonTea extends product
{
    public function __construct($weight)
    {
        $this->priceFor50 = 2.1;
        $this->weight = (double)$weight;
        $this->name = 'Лимонный чай';
    }
}
