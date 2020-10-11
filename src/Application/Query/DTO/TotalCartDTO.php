<?php

declare(strict_types=1);


namespace App\Application\Query\DTO;


class TotalCartDTO
{
    private string $currency;
    private float $total;
    private float $original;

    public function __construct(string $currency, float $total, float $original)
    {
        $this->currency = $currency;
        $this->total = $total;
        $this->original = $original;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getOriginal(): float
    {
        return $this->original;
    }
}
