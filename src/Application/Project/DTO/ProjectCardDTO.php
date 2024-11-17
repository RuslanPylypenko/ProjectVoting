<?php

namespace App\Application\Project\DTO;

use App\Domain\Project\Enum\ProjectStatus;
use App\Domain\Shared\Enum\Category;
use Money\Money;

class ProjectCardDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $budget,
        public string $category,
        public string $status,
        public int $votes,
    ) {
    }

    public static function createFromArray(array $data): static
    {
        $projectData = $data['project'] ?? [];
        /** @var Money $budget */
        $budget = $projectData['budget'];
        /** @var Category $category */
        $category = $projectData['category'];
        /** @var ProjectStatus $status */
        $status = $projectData['status'];

        return new static(
            $projectData['id'],
            $projectData['title'],
            sprintf('%s,%s', $budget->getAmount(), $budget->getCurrency()),
            $category->value,
            $status->getLabel(),
            $data['votes']
        );
    }
}
