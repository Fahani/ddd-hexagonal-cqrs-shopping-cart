<?php

declare(strict_types=1);


namespace App\Tests\Application\Query\GetTotalCartByCurrency;


use App\Application\Command\AddItemToCart\AddItemToCartCommand;
use App\Application\Command\AddItemToCart\AddItemToCartCommandHandler;
use App\Application\Query\GetTotalCartByCurrency\GetTotalCartByCurrencyQuery;
use App\Application\Query\GetTotalCartByCurrency\GetTotalCartByCurrencyQueryHandler;
use App\Domain\Exceptions\CurrencyNotFoundException;
use App\Infrastructure\EuropeanCentralBank\EcbCurrencyPort;
use App\Tests\FixtureBuilders\DataBuilders\CartRepositoryWithProducts;
use App\Tests\FixtureBuilders\DataBuilders\ProductRepositoryWithProducts;
use PHPUnit\Framework\TestCase;

class GetTotalCartByCurrencyTest extends TestCase
{

    /** @test */
    public function itShouldBeConvertedToUSD(): void
    {
        $cartRepository = CartRepositoryWithProducts::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();
        $currencyExchangeAdapter = new EcbCurrencyPort($_ENV['ECB_API']);

        $getTotalCartByCurrencyQuery = new GetTotalCartByCurrencyQuery('d875861a-bc68-4bd3-8085-439cd929fb43', 'USD');
        $getTotalCartByCurrencyQueryHandler = new GetTotalCartByCurrencyQueryHandler(
            $cartRepository,
            $productRepository,
            $currencyExchangeAdapter
        );

        $totalCartDTO = $getTotalCartByCurrencyQueryHandler->__invoke($getTotalCartByCurrencyQuery);

        $this->assertEquals('USD', $totalCartDTO->getCurrency());
        $this->assertEquals(2, $totalCartDTO->getOriginal());
    }

    /** @test */
    public function itShouldReturnTheSameCurrency(): void
    {
        $cartRepository = CartRepositoryWithProducts::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();
        $currencyExchangeAdapter = new EcbCurrencyPort($_ENV['ECB_API']);

        $getTotalCartByCurrencyQuery = new GetTotalCartByCurrencyQuery('d875861a-bc68-4bd3-8085-439cd929fb43', 'EUR');
        $getTotalCartByCurrencyQueryHandler = new GetTotalCartByCurrencyQueryHandler(
            $cartRepository,
            $productRepository,
            $currencyExchangeAdapter
        );

        $totalCartDTO = $getTotalCartByCurrencyQueryHandler->__invoke($getTotalCartByCurrencyQuery);

        $this->assertEquals('EUR', $totalCartDTO->getCurrency());
        $this->assertEquals(2, $totalCartDTO->getTotal());
    }

    /** @test */
    public function itShouldReturnPriceInDiscount(): void
    {
        $cartRepository = CartRepositoryWithProducts::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();

        $addItemToCartCommand
            = new AddItemToCartCommand(
            'd875861a-bc68-4bd3-8085-439cd929fb43',
            '5455deb6-0782-4be1-9564-1c9d046a1201',
            5
        );

        $addItemToCartCommandHandler = new AddItemToCartCommandHandler($cartRepository, $productRepository);
        $addItemToCartCommandHandler->__invoke($addItemToCartCommand);


        $currencyExchangeAdapter = new EcbCurrencyPort($_ENV['ECB_API']);

        $getTotalCartByCurrencyQuery = new GetTotalCartByCurrencyQuery('d875861a-bc68-4bd3-8085-439cd929fb43', 'EUR');
        $getTotalCartByCurrencyQueryHandler = new GetTotalCartByCurrencyQueryHandler(
            $cartRepository,
            $productRepository,
            $currencyExchangeAdapter
        );

        $totalCartDTO = $getTotalCartByCurrencyQueryHandler->__invoke($getTotalCartByCurrencyQuery);

        $this->assertEquals('EUR', $totalCartDTO->getCurrency());
        $this->assertEquals(6, $totalCartDTO->getTotal());
    }

    /** @test */
    public function itShouldThrowCurrencyNotFoundException(): void
    {
        $cartRepository = CartRepositoryWithProducts::createDefault();
        $productRepository = ProductRepositoryWithProducts::createDefault();
        $currencyExchangeAdapter = new EcbCurrencyPort($_ENV['ECB_API']);

        $getTotalCartByCurrencyQuery = new GetTotalCartByCurrencyQuery('d875861a-bc68-4bd3-8085-439cd929fb43', 'XCD');
        $getTotalCartByCurrencyQueryHandler = new GetTotalCartByCurrencyQueryHandler(
            $cartRepository,
            $productRepository,
            $currencyExchangeAdapter
        );
        $this->expectException(CurrencyNotFoundException::class);
        $getTotalCartByCurrencyQueryHandler->__invoke($getTotalCartByCurrencyQuery);
    }
}
