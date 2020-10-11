<?php

declare(strict_types=1);


namespace App\Domain\VO;




use App\Domain\CommonValidator;
use Common\Type\ValueObject;

class Natural extends ValueObject
{
    private int $value;

    public function __construct(int $value)
    {
        $this->guard($value);
        $this->value = $value;
    }

    private function guard(int $value): void
    {
        CommonValidator::validateIntGreaterOrEqualZero($value);
    }

    public function add(int $value): self
    {
        $this->guard($value);
        return new self($this->getValue()+$value);
    }

    public function subtract(int $value): self
    {
        $this->guard($value);
        return new self($this->getValue()-$value);
    }


    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param ValueObject|self $o
     *
     * @return bool
     */
    protected function equalValues(ValueObject $o): bool
    {
        return ($this->value === $o->getValue());
    }
}
