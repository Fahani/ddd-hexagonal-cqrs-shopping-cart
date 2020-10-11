<?php

declare(strict_types=1);


namespace App\Application\Command\RemoveItemFromCart;


use App\Domain\Repository\CartRepository;
use App\Domain\VO\CartId;
use App\Domain\VO\ProductId;

class RemoveItemFromCartCommandHandler
{
    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(RemoveItemFromCartCommand $removeItemFromCartCommand)
    {
        $cart = $this->cartRepository->findOrFailById(
            new CartId($removeItemFromCartCommand->getCartId())
        );

        $cart->removeCartItemOrFail(new ProductId($removeItemFromCartCommand->getProductId()));

        $this->cartRepository->save($cart);

    }
}
