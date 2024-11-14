<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Project\Exception\VoteRuleValidationException;
use App\Domain\Vote\Entity\VoteEntity;
use App\Infrastructure\Repository\VotesRepository;

class MaxVotesRule implements VotingRuleInterface
{
    public function __construct(
        private VotesRepository $votesRepository,
        private int $maxVotes = 1)
    {
    }

    public function validate(VoteEntity $vote): void
    {
        if ($this->votesRepository->getCountVotes($vote->getUser(), $vote->getProject()->getSession()) <= $this->maxVotes) {
            return;
        }
        
        throw new VoteRuleValidationException(sprintf('Cannot vote more than %s of votes.', $this->maxVotes));
    }
}
