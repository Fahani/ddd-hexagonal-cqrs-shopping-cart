<?php

declare(strict_types=1);


namespace App\Application\Command\AddItemToCart;


class AddItemToCartCommand
{
    private string $cartId;
    private string $productId;
    private int $unitsToAdd;

    public function __construct(string $cartId, string $productId, int $unitsToAdd)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->unitsToAdd = $unitsToAdd;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getUnitsToAdd(): int
    {
        return $this->unitsToAdd;
    }
}
