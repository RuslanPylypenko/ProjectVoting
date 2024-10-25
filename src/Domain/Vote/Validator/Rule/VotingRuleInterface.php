<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Vote\Entity\VoteEntity;

interface VotingRuleInterface
{
    public function validate(VoteEntity $vote): void;
}