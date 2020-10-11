<?php

declare(strict_types=1);


namespace App\Tests\Application\Command\RemoveItemFromCart;


use App\Application\Command\RemoveItemFromCart\RemoveItemFromCartCommand;
use App\Application\Command\RemoveItemFromCart\RemoveItemFromCartCommandHandler;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\VO\CartId;
use App\Tests\FixtureBuilders\DataBuilders\CartRepositoryWithProducts;
use App\Tests\FixtureBuilders\DataBuilders\EmptyCartRepository;
use PHPUnit\Framework\TestCase;

class RemoveItemFromCartTest extends TestCase
{
    /** @test */
    public function itShouldThrowEntityNotFoundException(): void
    {
        $cartRepository = EmptyCartRepository::createDefault();
        $removeItemFromCartCommand
            = new RemoveItemFromCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201'
        );

        $this->expectException(EntityNotFoundException::class);

        $removeItemToCartCommandHandler = new RemoveItemFromCartCommandHandler($cartRepository);
        $removeItemToCartCommandHandler->__invoke($removeItemFromCartCommand);
    }

    /** @test */
    public function itShouldRemoveItemFromCart(): void
    {
        $cartRepository = CartRepositoryWithProducts::createDefault();
        $removeItemFromCartCommand
            = new RemoveItemFromCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201'
        );

        $removeItemToCartCommandHandler = new RemoveItemFromCartCommandHandler($cartRepository);
        $removeItemToCartCommandHandler->__invoke($removeItemFromCartCommand);

        $cart = $cartRepository->findOrFailById(new CartId('d875861a-bc68-4bd3-8085-439cd929fb43'));

        $this->assertEmpty($cart->getCartItems());
    }

    /** @test */
    public function itShouldThrowEntityNotFoundExceptionWithAnInvalidCartId(): void
    {
        $cartRepository = CartRepositoryWithProducts::createDefault();
        $removeItemFromCartCommand
            = new RemoveItemFromCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb42',
            '5455deb6-0782-4be1-9564-1c9d046a1201'
        );
        $this->expectException(EntityNotFoundException::class);
        $removeItemToCartCommandHandler = new RemoveItemFromCartCommandHandler($cartRepository);
        $removeItemToCartCommandHandler->__invoke($removeItemFromCartCommand);
    }


}