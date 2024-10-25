<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\ProjectRuleValidationException;

class CategoryRule implements ProjectRulesInterface
{
    public function __construct(private array $categoryIds)
    {
    }

    public function validate(ProjectEntity $project): void
    {
        foreach ($project->getCategories() as $category) {
            if (in_array($category->getId(), $this->categoryIds)) {
                return;
            }
        }
        throw new ProjectRuleValidationException('Category is not valid');
    }
}