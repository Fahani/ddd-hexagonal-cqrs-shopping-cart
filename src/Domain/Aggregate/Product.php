<?php

declare(strict_types=1);


namespace App\Domain\Aggregate;



use App\Domain\VO\Money\Money;
use App\Domain\VO\Natural;
use Common\Type\AggregateRoot;
use Common\Type\Id;
use DateTime;
use DateTimeImmutable;

class Product extends AggregateRoot
{
    private Id $id;
    private Money $price;
    private Money $discountPrice;
    private Natural $unitsForDiscount;

    public function __construct(
        Id $id,
        DateTimeImmutable $createdAt,
        DateTime $updatedAt,
        Money $price,
        Money $discountPrice,
        Natural $unitsForDiscount
    ) {
        $this->id = $id;
        $this->price = $price;
        $this->discountPrice = $discountPrice;
        $this->unitsForDiscount = $unitsForDiscount;
        parent::__construct($createdAt, $updatedAt);
    }

    public function getId(): Id
    {
        return $this->id;
    }


    public function getPrice(Natural $unitsInCart): Money
    {
        if ($unitsInCart->getValue() > $this->unitsForDiscount->getValue()) {
            return $this->discountPrice;
        }

        return $this->price;
    }
}
