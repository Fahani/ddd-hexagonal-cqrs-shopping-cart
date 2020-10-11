<?php

declare(strict_types=1);


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Aggregate\Cart;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Repository\CartRepository;
use App\Domain\VO\CartId;

class InMemoryCartRepository implements CartRepository
{

    /** @var Cart[] */
    private array $carts;

    public function __construct()
    {
        $this->carts = [];
    }

    /**
     * @inheritDoc
     */
    public function findOrFailById(CartId $id): Cart
    {
        if (isset($this->carts[$id->getValue()->toString()])) {
            return $this->carts[$id->getValue()->toString()];
        }
        throw new EntityNotFoundException(
            "Couldn't find a cart with id {$id->getValue()->toString()}"
        );
    }

    public function save(Cart $cart): void
    {
        $this->carts[$cart->getId()->getValue()->toString()] = $cart;
    }
}
