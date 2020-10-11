<?php

declare(strict_types=1);


namespace App\Tests\Persistence\InMemory;


use App\Domain\Aggregate\Product;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\VO\Money\Amount;
use App\Domain\VO\Money\Currency;
use App\Domain\VO\Money\Money;
use App\Domain\VO\Natural;
use App\Domain\VO\ProductId;
use App\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InMemoryProductRepositoryTest extends TestCase
{
    /** @test */
    public function throwsNotFoundException(): void
    {
        $productRepository = new InMemoryProductRepository();
        $this->expectException(EntityNotFoundException::class);

        $productRepository->findOrFail(new ProductId(Uuid::uuid4()->toString()));
    }

    /** @test */
    public function savesAndFindProduct(): void
    {
        $productRepository = new InMemoryProductRepository();

        $product = new Product(
            new ProductId('5455deb6-0782-4be1-9564-1c9d046a1273'),
            new DateTimeImmutable(),
            new DateTime(),
            new Money(new Currency(Currency::DEFAULT_CURRENCY), new Amount(6)),
            new Money(new Currency(Currency::DEFAULT_CURRENCY), new Amount(3)),
            new Natural(5)
        );

        $productRepository->save($product);

        $productFromRepo = $productRepository->findOrFail(
            new ProductId('5455deb6-0782-4be1-9564-1c9d046a1273')
        );

        $this->assertEquals($product, $productFromRepo);
    }
}
