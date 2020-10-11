<?php

declare(strict_types=1);


namespace Common\Type;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Id extends ValueObject
{
    private UuidInterface $value;

    public function __construct(string $value)
    {
        $this->value = Uuid::fromString($value);
    }

    /**
     * @return UuidInterface
     */
    public function getValue(): UuidInterface
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
        return ($o->getValue() === $this->getValue());
    }
}
