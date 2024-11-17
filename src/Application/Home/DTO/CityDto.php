<?php

namespace App\Application\Home\DTO;

use App\Domain\City\Entity\CityEntity;

class CityDto
{
    public function __construct(
        public string $title,
    ) {
    }

    public static function fromEntity(CityEntity $city): static
    {
        return new static($city->getTitle());
    }
}