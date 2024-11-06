<?php

namespace App\Domain\Shared\Address\AddressLocator;

use App\Domain\Shared\Address\Address;

interface AddressLocatorInterface
{
    public function findAddress(array $payload): Address;
}
