<?php

declare(strict_types=1);


namespace Common\Type;


abstract class ValueObject
{
    /**
     * @param ValueObject|null $o
     *
     * If you need to check a nullable ValueObject use this format
     * NullableObjectService::equalsValueObject(
     *  isset($this->balance) ? $this->balance : null,
     *  isset($o->balance) ? $o->balance : null
     * )
     *
     * @return bool
     */
    public function equals(?ValueObject $o): bool
    {
        if ($o === null) {
            return $this === null;
        }
        return get_class($this) === get_class($o) && $this->equalValues($o);
    }


    abstract protected function equalValues(ValueObject $o): bool;
}
