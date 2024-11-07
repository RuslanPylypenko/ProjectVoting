<?php

namespace App\Application\Project\Command\Submit;

use App\Domain\Shared\Enum\Category;
use App\Infrastructure\Application\Command\CommandInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class SubmitProjectCommand implements CommandInterface
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $title;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 1)]
    public string $description;

    #[Assert\NotBlank()]
    #[Assert\Choice(
        choices: Category::VALUES,
        message: 'The category "{{ value }}" is not a valid choice.'
    )]
    public string $category;

    #[Assert\NotBlank()]
    #[Assert\Type('float')]
    public float $budget;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 1, max: 255)]
    public string $street;

    #[Assert\Length(min: 1, max: 255)]
    #[SerializedName('house_number')]
    public string $houseNumber;
}
