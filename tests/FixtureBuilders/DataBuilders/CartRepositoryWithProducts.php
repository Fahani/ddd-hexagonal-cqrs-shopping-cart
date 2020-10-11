<?php

declare(strict_types=1);


namespace App\Tests\FixtureBuilders\DataBuilders;


use App\Domain\Aggregate\Cart;
use App\Domain\Repository\CartRepository;
use App\Domain\VO\CartId;
use App\Domain\VO\CartItem;
use App\Domain\VO\Natural;
use App\Domain\VO\ProductId;
use App\Infrastructure\Persistence\InMemory\InMemoryCartRepository;
use DateTime;
use DateTimeImmutable;

class CartRepositoryWithProducts
{
    public static function createDefault(): CartRepository
    {
        $productRepository = ProductRepositoryWithProducts::createDefault();
        $cartRepository = new InMemoryCartRepository();

        $cart = new Cart(
            new CartId('d875861a-bc68-4bd3-8085-439cd929fb43'),
            new DateTimeImmutable(),
            new DateTime()
        );

        $cart->addCartItem(
            $productRepository,
            new CartItem(
                new ProductId('5455deb6-0782-4be1-9564-1c9d046a1201'),
                new Natural(1)
            )
        );

        $cartRepository->save($cart);

        return $cartRepository;
    }
}