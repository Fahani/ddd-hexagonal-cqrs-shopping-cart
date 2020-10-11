<?php

declare(strict_types=1);


namespace App\Domain\VO\Money;


use App\Domain\Exceptions\DomainException;
use Common\Type\ValueObject;

class Money extends ValueObject
{
    private Currency $currency;
    private Amount $amount;

    public function __construct(Currency $currency, Amount $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function subtract(Money $money): self
    {
        $this->guardSameCurrency($money);

        return new self(
            $this->currency,
            $this->getAmount()->subtract($money->getAmount())
        );
    }

    public function add(Money $money): self
    {
        $this->guardSameCurrency($money);

        return new self(
            $this->currency,
            $this->getAmount()->add($money->getAmount())
        );
    }

    public function guardSameCurrency(Money $money): void
    {
        if (!$money->getCurrency()->equals($this->currency)) {
            throw new DomainException(
                sprintf(
                    'This currency_ %s it is not the same type currency than the current currency which is %s',
                    $this->currency->getValue(),
                    $money->getCurrency()->getValue()
                )
            );
        }
    }

    public function isBiggerThan(Money $money): bool
    {
        if ($this->currency->equals($money->getCurrency())) {
            return $this->amount->equals($money->amount);
        }
        return false;
    }

    /**
     * @param ValueObject|self $o
     *
     * @return bool
     */
    public function equalValues(ValueObject $o): bool
    {
        return ($this->currency->equals($o->getCurrency())
            && $this->amount->equals($o->getAmount()));
    }
}
