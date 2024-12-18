<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\VoteRuleValidationException;

class CategoryRule implements ProjectRulesInterface
{
    public function __construct(private array $categoryIds)
    {
    }

    /**
     * @throws VoteRuleValidationException
     */
    public function validate(ProjectEntity $project): void
    {
        if (in_array($project->getCategory()->value, $this->categoryIds)) {
            return;
        }

        throw new VoteRuleValidationException(sprintf('The category "%s" is not valid. Allowed categories are: %s.', $project->getCategory()->value, implode(', ', $this->categoryIds)));
    }
}
