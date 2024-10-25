<?php

namespace App\Application\Project\Command\Submit;

use Symfony\Component\Validator\Constraints as Assert;

class SubmitProjectCommand
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $title;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 1)]
    public string $description;

    #[Assert\NotBlank()]
    #[Assert\Type('float')]
    public float $budget;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $street;

    #[Assert\Blank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $houseNumber;
}