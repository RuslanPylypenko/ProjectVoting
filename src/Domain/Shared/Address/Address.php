<?php

namespace App\Domain\Shared\Address;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Address
{
    #[Column(name: 'house_number', type: Types::STRING, length: 36, nullable: true)]
    private ?string $houseNumber;

    #[Column(name: 'street', type: Types::STRING, length: 255, nullable: true)]
    private ?string $street;

    #[Column(name: 'postal_code', type: Types::STRING, length: 16, nullable: true)]
    private ?int $postalCode;

    #[Column(name: 'city', type: Types::STRING, length: 255)]
    private string $city;

    #[Column(name: 'country', type: Types::STRING, length: 255)]
    private string $country;

    //=============================================

    public function __construct(
        string $city,
        string $country,
        ?string $street = null,
        ?string $postalCode = null,
        ?string $houseNumber = null,
    ) {
        $this->city = $city;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
    }

    //=============================================

    public function getFullAddress(): string
    {
        return sprintf(
            '%s, %s м. %s, %s, %s',
            $this->postalCode, 
            $this->country, 
            $this->city, 
            $this->street, 
            $this->houseNumber
        );
    }

    public function getShortAddress(): string
    {
        return sprintf('м. %s, %s, %s', $this->city, $this->street, $this->houseNumber);
    }

    public function isSameCity(self $address): bool
    {
        return $address->city === $this->city;
    }
}