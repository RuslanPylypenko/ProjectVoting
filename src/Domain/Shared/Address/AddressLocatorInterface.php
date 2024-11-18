<?php

namespace App\Domain\Shared\Address;

interface AddressLocatorInterface
{
    public function findAddress(array $payload): Address;
}
