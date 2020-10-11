<?php

declare(strict_types=1);


namespace App\Domain\VO\Money;


use App\Domain\CommonValidator;
use Common\Type\ValueObject;

class Currency extends ValueObject
{
    public const DEFAULT_CURRENCY = 'EUR';

    private string $value;

    public function __construct(string $value)
    {
        $this->guard($value);
        $this->value = $value;
    }

    private function guard(string $value): void
    {
        CommonValidator::validateNotEmptyString($value);
        CommonValidator::validateCurrency($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param self|ValueObject $o
     *
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return ($this->value === $o->getValue());
    }

}
