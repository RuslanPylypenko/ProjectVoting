<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Project\Exception\VoteRuleValidationException;
use App\Domain\Vote\Entity\VoteEntity;
use App\Infrastructure\Repository\VotesRepository;

class UniqueVoteRule implements VotingRuleInterface
{
    public function __construct(private VotesRepository $votesRepository)
    {
    }

    public function validate(VoteEntity $vote): void
    {
        if (null !== $vote = $this->votesRepository->findUserVote($vote->getProject(), $vote->getUser())) {
            $voteDateTime = $vote->getCreatedAt()->format('Y-m-d H:i:s');
            throw new VoteRuleValidationException(sprintf('User has already voted on %s.', $voteDateTime));
        }
    }
}
