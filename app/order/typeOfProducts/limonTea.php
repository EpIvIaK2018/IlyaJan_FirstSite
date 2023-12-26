<?php
use App\order\product;
class limonTea extends product
{
    public function __construct($weight)
    {
        $this->priceFor50 = 2.1;
        $this->weight = (double)$weight;
        $this->name = 'Лимонный чай';
    }
}
