<?php
use App\order\product;
class lastTea extends product
{
    public function __construct($weight)
    {
        $this->priceFor50 = 3.5;
        $this->weight = (double)$weight;
        $this->name = 'Последний чай';
    }



}
