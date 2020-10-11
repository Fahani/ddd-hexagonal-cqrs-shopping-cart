<?php

declare(strict_types=1);


namespace App\Application\Command\AddItemToCart;


use App\Domain\Repository\CartRepository;
use App\Domain\Repository\ProductRepository;
use App\Domain\VO\CartId;
use App\Domain\VO\CartItem;
use App\Domain\VO\Natural;
use App\Domain\VO\ProductId;

class AddItemToCartCommandHandler
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function __invoke(AddItemToCartCommand $addItemToCartCommand)
    {
        $cart = $this->cartRepository->findOrFailById(new CartId($addItemToCartCommand->getCartId()));

        $cart->addCartItem(
            $this->productRepository,
            new CartItem(
                new ProductId($addItemToCartCommand->getProductId()),
                new Natural($addItemToCartCommand->getUnitsToAdd())
            )
        );

        $this->cartRepository->save($cart);
    }
}
