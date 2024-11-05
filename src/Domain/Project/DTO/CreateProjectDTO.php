<?php

namespace App\Domain\Project\DTO;

use App\Application\Project\Command\Submit\SubmitProjectCommand;
use Money\Money;

class CreateProjectDTO
{
    public string $title;
    public string $description;
    public int $category;
    public Money $budget;
    public string $street;
    public ?string $houseNumber = null;

    public static function fromCommand(SubmitProjectCommand $command): self
    {
        return new self();
    }
}