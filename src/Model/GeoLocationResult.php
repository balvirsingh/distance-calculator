<?php

namespace App\Model;

class GeoLocationResult
{
    private string $name;
    private string $address;
    private float $distance;

    public function __construct(string $name, string $address, float $distance)
    {
        $this->name = $name;
        $this->address = $address;
        $this->distance = $distance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }
}