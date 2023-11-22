<?php
namespace App\order;
require_once 'product.php';
class redTea extends product
{
    public function __construct($weight)
    {
        $this->priceFor50 = 2.5;
        $this->weight = (double)$weight;
        $this->name = 'Красный чай';
    }



}
