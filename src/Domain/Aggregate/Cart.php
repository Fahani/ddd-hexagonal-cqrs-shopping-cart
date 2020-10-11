<?php

declare(strict_types=1);


namespace App\Domain\Aggregate;


use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\MaxDifferentProductsInCartException;
use App\Domain\Exceptions\MaxUnitPerProductException;
use App\Domain\Repository\ProductRepository;
use App\Domain\VO\CartItem;
use App\Domain\VO\Money\Amount;
use App\Domain\VO\Money\Currency;
use App\Domain\VO\Money\Money;
use App\Domain\VO\Natural;
use App\Domain\VO\ProductId;
use Common\Type\AggregateRoot;
use Common\Type\Id;
use DateTime;
use DateTimeImmutable;

class Cart extends AggregateRoot
{
    public const MAX_UNIT_PER_PRODUCT = 50;
    public const MAX_DIFFERENT_PRODUCTS_IN_CART = 10;

    private Id $id;
    /** @var CartItem[] */
    private array $cartItems;

    public function __construct(Id $id, DateTimeImmutable $createdAt, DateTime $updatedAt)
    {
        $this->id = $id;
        $this->cartItems = [];
        parent::__construct($createdAt, $updatedAt);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Product[]
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    public function addCartItem(ProductRepository $productRepository, CartItem $cartItem): void
    {
        $productRepository->findOrFail($cartItem->getProductId());

        if ($this->isCartItemInCartByProductId($cartItem->getProductId())) {
            $existingCartItem = $this->cartItems[$cartItem->getProductId()->getValue()->toString()];

            $newUnits = $existingCartItem->getUnits()->add($cartItem->getUnits()->getValue());

            if ($newUnits->getValue() > self::MAX_UNIT_PER_PRODUCT) {
                $this->throwMaxUnitPerProductException($cartItem, $newUnits);
            }

            $this->cartItems[$cartItem->getProductId()->getValue()->toString()]
                = new CartItem($cartItem->getProductId(), $newUnits);


        } elseif (count($this->cartItems) >= self::MAX_DIFFERENT_PRODUCTS_IN_CART) {
            throw new MaxDifferentProductsInCartException(
                'Reached the max number of items in cart:' . self::MAX_DIFFERENT_PRODUCTS_IN_CART
            );
        } elseif ($cartItem->getUnits()->getValue() > self::MAX_UNIT_PER_PRODUCT) {
            $this->throwMaxUnitPerProductException($cartItem, $cartItem->getUnits());
        } else {
            $this->cartItems[$cartItem->getProductId()->getValue()->toString()] = $cartItem;
        }
    }

    private function isCartItemInCartByProductId(ProductId $productId): bool
    {
        return isset($this->cartItems[$productId->getValue()->toString()]);
    }

    public function removeCartItemOrFail(ProductId $productId): void
    {
        if (!$this->isCartItemInCartByProductId($productId)) {
            throw new EntityNotFoundException(
                "Cart item with product id {$productId->getValue()->toString()} not found"
            );
        }

        unset($this->cartItems[$productId->getValue()->toString()]);
    }

    public function getTotalCartItems(ProductRepository $productRepository): Money
    {
        $money = new Money(new Currency(Currency::DEFAULT_CURRENCY), new Amount(0));

        foreach ($this->cartItems as $item) {
            $product = $productRepository->findOrFail($item->getProductId());

            $money = $money->add(
                new Money(
                    new Currency(Currency::DEFAULT_CURRENCY),
                    $product->getPrice($item->getUnits())->getAmount()->multiplyBy((float)$item->getUnits()->getValue())
                )
            );
        }

        return $money;
    }

    private function throwMaxUnitPerProductException(CartItem $cartItem, Natural $newUnits): void
    {
        throw new MaxUnitPerProductException(
            "This product ({$cartItem->getProductId()->getValue()->toString()}) can't be added 
                    to the cart. The units would be {$newUnits->getValue()} and the max is " . self::MAX_UNIT_PER_PRODUCT
        );
    }
}
