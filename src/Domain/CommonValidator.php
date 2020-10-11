<?php

declare(strict_types=1);


namespace App\Domain;


use App\Domain\Exceptions\DomainException;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Validation as SymfonyValidation;

class CommonValidator
{
    public static function validateNotEmptyString(string $string): void
    {
        if (empty($string)) {
            throw new DomainException('String is empty: ' . $string);
        }
    }

    public static function validateCurrency(string $currency): void
    {
        $validator = SymfonyValidation::createValidator();
        $violations = $validator->validate($currency, [new Currency()]);

        if (count($violations) > 0) {
            throw new DomainException($violations[0]->getMessage());
        }
    }

    public static function validateFloatGreaterOrEqualZero(float $value):void {
        if ($value < 0) {
            throw new DomainException('Value is less than 0: ' . $value);
        }
    }

    public static function validateIntGreaterOrEqualZero(int $value):void {
        if ($value < 0) {
            throw new DomainException('Value is less than 0: ' . $value);
        }
    }
}
