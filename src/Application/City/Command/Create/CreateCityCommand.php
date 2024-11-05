<?php

namespace App\Application\City\Command\Create;

use App\Infrastructure\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCityCommand implements CommandInterface
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $title;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $slug;
}