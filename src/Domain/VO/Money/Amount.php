<?php

declare(strict_types=1);


namespace App\Domain\VO\Money;


use App\Domain\CommonValidator;
use Common\Type\ValueObject;

class Amount extends ValueObject
{
    private float $value;

    public function __construct(float $value)
    {
        $this->guard($value);
        $this->value = $value;
    }

    private function guard(float $value): void
    {
        CommonValidator::validateFloatGreaterOrEqualZero($value);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function add(Amount $amount): self
    {
        return new self($this->value + $amount->value);
    }

    public function subtract(Amount $amount): self
    {
        return new self($this->value - $amount->value);
    }

    public function multiplyBy(float $multiplier): self
    {
        return new self($this->getValue() * $multiplier);
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
