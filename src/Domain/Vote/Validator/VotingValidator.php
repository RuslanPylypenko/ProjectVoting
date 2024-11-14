<?php

namespace App\Domain\Vote\Validator;

use App\Domain\Vote\Entity\VoteEntity;
use App\Domain\Vote\Validator\Rule\VotingRuleInterface;

class VotingValidator
{
    /**
     * @param VotingRuleInterface[] $rules
     */
    public function validate(VoteEntity $vote, array $rules): void
    {
        foreach ($rules as $rule) {
            $rule->validate($vote);
        }
    }
}
