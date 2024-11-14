<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\VoteRuleValidationException;
use Money\Money;

class BudgetRule implements ProjectRulesInterface
{
    public function __construct(private Money $minBudget, private Money $maxBudget)
    {
    }

    public function validate(ProjectEntity $project): void
    {
        if ($project->getBudget()->lessThan($this->maxBudget) && $project->getBudget()->greaterThan($this->minBudget)) {
            return;
        }

        throw new VoteRuleValidationException(
            sprintf(
                'The budget of %s is not within the allowed range. It must be between %s and %s.',
                $project->getBudget()->getAmount(),
                $this->minBudget->getAmount(),
                $this->maxBudget->getAmount()
            )
        );
    }
}
