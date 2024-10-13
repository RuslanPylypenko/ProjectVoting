<?php

namespace App\User\Api\Auth\Registration;

use App\Shared\Api\CommandInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationCommand implements CommandInterface
{
    #[Assert\NotBlank]
    public $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 255)]
    public string $password;
}