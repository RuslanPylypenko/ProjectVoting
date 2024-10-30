<?php

namespace App\Domain\User\DTO;

use App\Domain\User\Entity\UserEntity;

class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $birth_date,
        public string $living_address,
        public string $registration_address,
    ) {
    }

    public static function fromEntity(UserEntity $user): self
    {
        return new self(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getBirthDate()->format('Y-m-d'),
            $user->getLivingAddress()->getFullAddress(),
            $user->getRegistrationAddress()->getFullAddress(),
        );
    }
}