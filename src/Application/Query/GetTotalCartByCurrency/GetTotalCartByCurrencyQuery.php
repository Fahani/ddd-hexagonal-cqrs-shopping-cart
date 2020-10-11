<?php

declare(strict_types=1);


namespace App\Application\Query\GetTotalCartByCurrency;


class GetTotalCartByCurrencyQuery
{
    private string $cartId;
    private string $currency;

    public function __construct(string $cartId, string $currency)
    {
        $this->cartId = $cartId;
        $this->currency = $currency;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
