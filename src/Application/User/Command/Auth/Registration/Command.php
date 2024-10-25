<?php

namespace App\Application\User\Command\Auth\Registration;

use App\Infrastructure\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements CommandInterface
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