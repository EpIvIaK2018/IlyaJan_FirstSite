<?php
namespace App\order;

abstract class product
{
    public float $priceFor50;
    public float $weight;

    public string $name = '';


    public function getSum(): float
    {
        return ($this->weight / 50) * $this->priceFor50;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getCount(): string{
        return $this->weight;
    }
}