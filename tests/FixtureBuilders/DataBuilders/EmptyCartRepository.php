<?php

declare(strict_types=1);


namespace App\Tests\FixtureBuilders\DataBuilders;


use App\Domain\Aggregate\Cart;
use App\Domain\Repository\CartRepository;
use App\Domain\VO\CartId;
use App\Infrastructure\Persistence\InMemory\InMemoryCartRepository;
use DateTime;
use DateTimeImmutable;

class EmptyCartRepository
{
    public static function createDefault(): CartRepository
    {
        $cartRepository = new InMemoryCartRepository();

        $cart = new Cart(
            new CartId('d875861a-bc68-4bd3-8085-439cd929fb43'),
            new DateTimeImmutable(),
            new DateTime()
        );

        $cartRepository->save($cart);

        return $cartRepository;
    }
}
