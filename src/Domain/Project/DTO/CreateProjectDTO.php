<?php

namespace App\Domain\Project\DTO;

use App\Application\Project\Command\Submit\SubmitProjectCommand;
use App\Domain\Project\Entity\CategoryEntity;
use Money\Money;

class CreateProjectDTO
{
    public string $title;
    public string $description;
    public Money $budget;
    public string $street;
    public ?string $houseNumber = null;

    /** @var CategoryEntity[] */
    public array $categories;

    public static function fromCommand(SubmitProjectCommand $command): self
    {
        return new self();
    }
}