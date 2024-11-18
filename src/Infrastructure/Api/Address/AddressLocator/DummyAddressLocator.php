<?php

namespace App\Infrastructure\Api\Address\AddressLocator;

use App\Domain\Shared\Address\Address;
use App\Domain\Shared\Address\AddressLocatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DummyAddressLocator implements AddressLocatorInterface
{
    public function findAddress(array $payload): Address
    {
        return new Address(
            $payload['city'],
            'Україна',
            $payload['street'],
            '01102',
            null,
        );
    }
}
