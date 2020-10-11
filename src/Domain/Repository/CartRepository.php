<?php

declare(strict_types=1);


namespace App\Domain\Repository;


use App\Domain\Aggregate\Cart;
use App\Domain\Exceptions\DomainException;
use App\Domain\VO\CartId;

interface CartRepository
{
    /**
     * @param CartId $id
     * @return Cart
     * @throws DomainException
     */
    public function findOrFailById(CartId $id): Cart;

    public function save(Cart $cart): void;
}
