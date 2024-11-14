<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Project\Exception\VoteRuleValidationException;
use App\Domain\Vote\Entity\VoteEntity;

class AgeRule implements VotingRuleInterface
{
    public function __construct(private int $minAge = 18)
    {
    }

    public function validate(VoteEntity $vote): void
    {
        if ($vote->getUser()->getAge() >= $this->minAge) {
            return;
        }

        throw new VoteRuleValidationException('Age rule cannot be less than '.$this->minAge);
    }
}
