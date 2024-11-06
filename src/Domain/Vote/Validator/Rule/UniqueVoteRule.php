<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Vote\Entity\VoteEntity;

class UniqueVoteRule implements VotingRuleInterface
{
    public function validate(VoteEntity $vote): void
    {
        // TODO: Implement validate() method.
    }
}
