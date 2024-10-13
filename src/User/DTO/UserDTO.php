<?php

namespace App\User\DTO;

use App\User\Entity\UserEntity;

class UserDTO
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct(int $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public static function fromEntity(UserEntity $user): self
    {
        return new self(
            $user->getId(),
            $user->getName(),
            $user->getEmail()
        );
    }
}