<?php
declare(strict_types=1);


namespace App\Domain\Repository;


use App\Domain\Aggregate\Product;
use App\Domain\Exceptions\DomainException;
use App\Domain\VO\ProductId;

interface ProductRepository
{
    /**
     * @param ProductId $productId
     * @return Product
     * @throws DomainException
     */
    public function findOrFail(ProductId $productId): Product;

    public function save(Product $product): void;
}
