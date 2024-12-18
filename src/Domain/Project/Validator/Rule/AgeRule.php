<?php

namespace App\Domain\Project\Validator\Rule;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Exception\VoteRuleValidationException;

class AgeRule implements ProjectRulesInterface
{
    public function __construct(private int $minAge = 18)
    {
    }

    public function validate(ProjectEntity $project): void
    {
        if ($project->getAuthor()->getAge() >= $this->minAge) {
            return;
        }

        throw new VoteRuleValidationException('Age rule cannot be less than '.$this->minAge);
    }
}
