<?php

declare(strict_types=1);


namespace App\Domain;


use App\Domain\Exceptions\CurrencyNotFoundException;
use App\Domain\VO\Money\Currency;
use App\Domain\VO\Money\Money;

interface CurrencyExchangeAdapter
{

    /**
     * @param Money $money
     * @param Currency $currency
     * @return Money
     * @throws CurrencyNotFoundException
     */
    public function convertOrFail(Money $money, Currency $currency): Money;
}
