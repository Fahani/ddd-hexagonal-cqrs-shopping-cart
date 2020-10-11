<?php

declare(strict_types=1);


namespace App\Application\Query\GetTotalCartByCurrency;


use App\Application\Query\DTO\TotalCartDTO;
use App\Domain\CurrencyExchangeAdapter;
use App\Domain\Repository\CartRepository;
use App\Domain\Repository\ProductRepository;
use App\Domain\VO\CartId;
use App\Domain\VO\Money\Currency;

class GetTotalCartByCurrencyQueryHandler
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private CurrencyExchangeAdapter $exchangeAdapter;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        CurrencyExchangeAdapter $exchangeAdapter
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->exchangeAdapter = $exchangeAdapter;
    }

    public function __invoke(GetTotalCartByCurrencyQuery $query): TotalCartDTO
    {
        $currency = new Currency($query->getCurrency());

        if ($query->getCurrency() !== Currency::DEFAULT_CURRENCY) {
            $cart = $this->cartRepository->findOrFailById(new CartId($query->getCartId()));
            $totalItemsMoney = $cart->getTotalCartItems($this->productRepository);

            $newMoney = $this->exchangeAdapter->convertOrFail($totalItemsMoney, $currency);

            return new TotalCartDTO(
                $newMoney->getCurrency()->getValue(),
                $newMoney->getAmount()->getValue(),
                $totalItemsMoney->getAmount()->getValue()
            );
        }
        $cart = $this->cartRepository->findOrFailById(new CartId($query->getCartId()));
        $totalItemsMoney = $cart->getTotalCartItems($this->productRepository);

        return new TotalCartDTO(
            $totalItemsMoney->getCurrency()->getValue(),
            $totalItemsMoney->getAmount()->getValue(),
            $totalItemsMoney->getAmount()->getValue()
        );
    }
}
