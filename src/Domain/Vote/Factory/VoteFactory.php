<?php

namespace App\Domain\Vote\Factory;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\User\Entity\UserEntity;
use App\Domain\Vote\Entity\VoteEntity;
use App\Domain\Vote\Validator\Rule\RuleFactory;
use App\Domain\Vote\Validator\VotingValidator;

class VoteFactory
{
    public function __construct(
        private VotingValidator $votingValidator,
        private RuleFactory $ruleFactory,
    ) {
    }

    public function createVote(UserEntity $user, ProjectEntity $project, SessionEntity $session): VoteEntity
    {
        $vote = new VoteEntity(
            project: $project,
            user: $user
        );

        $this->votingValidator->validate(
            $vote,
            $this->ruleFactory->createRules($session)
        );

        return $vote;
    }
}