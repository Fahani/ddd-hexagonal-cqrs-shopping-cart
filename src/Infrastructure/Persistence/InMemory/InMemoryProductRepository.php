<?php

declare(strict_types=1);


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Aggregate\Product;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repository\ProductRepository;
use App\Domain\VO\ProductId;

class InMemoryProductRepository implements ProductRepository
{
    /**
     * @var Product[]
     */
    private array $products;


    public function __construct()
    {
        $this->products = [];
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(ProductId $productId): Product
    {
        if (isset($this->products[$productId->getValue()->toString()])) {
            return $this->products[$productId->getValue()->toString()];
        }
        throw new EntityNotFoundException(
            "Couldn't find product with id {$productId->getValue()->toString()}"
        );
    }

    public function save(Product $product): void
    {
        $this->products[$product->getId()->getValue()->toString()] = $product;
    }
}
