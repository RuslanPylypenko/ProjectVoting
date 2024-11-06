<?php

namespace App\Domain\Shared\Address\AddressLocator;

use App\Domain\Shared\Address\Address;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CustomAddressLocator implements AddressLocatorInterface
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function findAddress(array $payload): Address
    {
        $response = $this->client->request(
            method: 'GET',
            url: 'https://google.com',
        );

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Something went wrong');
        }

        return new Address(
            'Kyiv',
            'Ukraine',
            $payload['street'],
            '01102',
            null,
        );
    }
}
