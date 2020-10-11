<?php

declare(strict_types=1);


namespace App\Tests\Application\Command\AddItemToCart;


use App\Application\Command\AddItemToCart\AddItemToCartCommand;
use App\Application\Command\AddItemToCart\AddItemToCartCommandHandler;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\MaxDifferentProductsInCartException;
use App\Domain\Exceptions\MaxUnitPerProductException;
use App\Domain\VO\CartId;
use App\Domain\VO\CartItem;
use App\Domain\VO\Natural;
use App\Domain\VO\ProductId;
use App\Tests\FixtureBuilders\DataBuilders\EmptyCartRepository;
use App\Tests\FixtureBuilders\DataBuilders\ProductRepositoryWithProducts;
use PHPUnit\Framework\TestCase;

class AddItemToCartTest extends TestCase
{
    /** @test */
    public function itShouldAddItemToCart(): void
    {
        $cartRepository = EmptyCartRepository::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            1
        );

        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);

        $cart = $cartRepository->findOrFailById(new CartId('d875861a-bc68-4bd3-8085-439cd929fb43'));

        $carItemsFromCart = $cart->getCartItems();


        $cartItem = new CartItem(new ProductId('5455deb6-0782-4be1-9564-1c9d046a1201'), new Natural(1));

        $this->assertEquals([$cartItem->getProductId()->getValue()->toString() => $cartItem], $carItemsFromCart);
    }

    /** @test */
    public function itShouldThrowEntityNotFoundException(): void
    {
        $cartRepository = EmptyCartRepository::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1212',
            1
        );
        $this->expectException(EntityNotFoundException::class);

        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);
    }

    /** @test */
    public function itShouldThrowMaxUnitPerProductExceptionWithOneCartItem(): void
    {
        $cartRepository = EmptyCartRepository::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            51
        );
        $this->expectException(MaxUnitPerProductException::class);

        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);
    }

    /** @test */
    public function itShouldThrowMaxUnitPerProductExceptionWithMultipleCartItems(): void
    {
        $cartRepository = EmptyCartRepository::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();
        $this->expectException(MaxUnitPerProductException::class);

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            20
        );

        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            30
        );

        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            1
        );


        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);
    }

    /** @test */
    public function itShouldThrowMaxDifferentProductsInCartException(): void
    {
        $cartRepository = EmptyCartRepository::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();
        $productsId = [
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            '5455deb6-0782-4be1-9564-1c9d046a1202',
            '5455deb6-0782-4be1-9564-1c9d046a1203',
            '5455deb6-0782-4be1-9564-1c9d046a1204',
            '5455deb6-0782-4be1-9564-1c9d046a1205',
            '5455deb6-0782-4be1-9564-1c9d046a1206',
            '5455deb6-0782-4be1-9564-1c9d046a1207',
            '5455deb6-0782-4be1-9564-1c9d046a1208',
            '5455deb6-0782-4be1-9564-1c9d046a1209',
            '5455deb6-0782-4be1-9564-1c9d046a1210',
            '5455deb6-0782-4be1-9564-1c9d046a1211'
        ];

        $this->expectException(MaxDifferentProductsInCartException::class);

        foreach ($productsId as $productId) {
            $addItemToCartCommand =
                new AddItemToCartCommand('d875861a-bc68-4bd3-8085-439cd929fb43', $productId, 1);

            $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
            $addItemToCartCommandHandler->__invoke($addItemToCartCommand);
        }
    }
}
