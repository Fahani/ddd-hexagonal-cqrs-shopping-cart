<?php

declare(strict_types=1);


namespace App\Infrastructure\EuropeanCentralBank;


use App\Domain\CurrencyExchangeAdapter;
use App\Domain\Exceptions\CurrencyNotFoundException;
use App\Domain\VO\Money\Currency;
use App\Domain\VO\Money\Money;

class EcbCurrencyPort implements CurrencyExchangeAdapter
{

    private string $ecbApi;

    public function __construct(string $ecbApi)
    {
        $this->ecbApi = $ecbApi;
    }


    /**
     * @inheritDoc
     */
    public function convertOrFail(Money $money, Currency $currency): Money
    {
        $xml = simplexml_load_string(file_get_contents($this->ecbApi));

        foreach ($xml->Cube->Cube->Cube as $rate) {
            if (((string)$rate['currency']) === $currency->getValue()) {
                return new Money(
                    new Currency($currency->getValue()),
                    $money->getAmount()->multiplyBy((float)$rate['rate'])
                );
            }
        }

        throw new CurrencyNotFoundException("Currency {$currency->getValue()} not found");
    }
}
