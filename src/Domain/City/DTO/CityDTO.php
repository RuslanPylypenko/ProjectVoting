<?php

namespace App\Domain\City\DTO;

use App\Domain\City\Entity\CityEntity;

class CityDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $address,
    ) {
    }

    public static function fromEntity(CityEntity $city): self
    {
        return new self(
            $city->getId(),
            $city->getTitle(),
            $city->getSlug(),
            $city->getAddress()->getFullAddress(),
        );
    }
}
