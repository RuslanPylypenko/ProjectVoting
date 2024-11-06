<?php

namespace App\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Money\Currency;
use Money\Money;
use Money\UnknownCurrencyException;

class MoneyType extends Type
{
    public const MONEY = 'money';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    /**
     * @throws UnknownCurrencyException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Money
    {
        if (null === $value) {
            return null;
        }

        [$amount, $currency] = explode(' ', $value);

        return new Money((float) $amount, new Currency($currency));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Money) {
            throw new \InvalidArgumentException('Value must be an instance of "Money".');
        }

        return $value->getAmount().' '.$value->getCurrency()->getName();
    }

    public function getName(): string
    {
        return self::MONEY;
    }
}
