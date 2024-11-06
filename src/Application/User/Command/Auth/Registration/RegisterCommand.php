<?php

namespace App\Application\User\Command\Auth\Registration;

use App\Infrastructure\Application\Command\CommandInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterCommand implements CommandInterface
{
    #[Assert\NotBlank]
    public $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 255)]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 255)]
    #[SerializedName('living_address')]
    public string $livingAddress;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 255)]
    #[SerializedName('registration_address')]
    public string $registrationAddress;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 255)]
    #[Assert\DateTime(format: 'Y-m-d')]
    #[SerializedName('birth_date')]
    public string $birthDate;
}
