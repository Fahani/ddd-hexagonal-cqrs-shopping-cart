<?php

declare(strict_types=1);


namespace App\Application\Command\RemoveItemFromCart;


class RemoveItemFromCartCommand
{
    private string $cartId;
    private string $productId;

    public function __construct(string $cartId, string $productId)
    {
        $this->productId = $productId;
        $this->cartId = $cartId;
    }

    public function getCartId(): string
    {
        return $this->cartId;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}
