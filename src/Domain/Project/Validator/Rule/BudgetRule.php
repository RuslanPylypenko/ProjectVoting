<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\ProjectRuleValidationException;
use Money\Money;

class BudgetRule implements ProjectRulesInterface
{
    public function __construct(private Money $minBudget, private Money $maxBudget)
    {
    }

    public function validate(ProjectEntity $project): void
    {
        if ($project->getBudget()->greaterThan($this->minBudget) || $project->getBudget()->lessThan($this->maxBudget)) {
            return;
        }

        throw new ProjectRuleValidationException('Budget must be greater than the minimum budget and less than the maximum budget');
    }
}
