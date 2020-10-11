<?php

declare(strict_types=1);


namespace App\Tests\FixtureBuilders\DataBuilders;


use App\Domain\Aggregate\Product;
use App\Domain\Repository\ProductRepository;
use App\Domain\VO\Money\Amount;
use App\Domain\VO\Money\Currency;
use App\Domain\VO\Money\Money;
use App\Domain\VO\Natural;
use App\Domain\VO\ProductId;
use App\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use DateTime;
use DateTimeImmutable;

class ProductRepositoryWithProducts
{
    public static function createDefault(): ProductRepository
    {
        $productRepository = new InMemoryProductRepository();

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

        foreach ($productsId as $productId) {
            $product = new Product(
                new ProductId($productId),
                new DateTimeImmutable(),
                new DateTime(),
                new Money(new Currency(Currency::DEFAULT_CURRENCY), new Amount(2)),
                new Money(new Currency(Currency::DEFAULT_CURRENCY), new Amount(1)),
                new Natural(5)
            );

            $productRepository->save($product);
        }

        return $productRepository;
    }
}
