<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Session\Entity\SessionEntity;
use App\Infrastructure\Repository\VotesRepository;

class RuleFactory
{
    public function __construct(
        private VotesRepository $votesRepository,
    ) {
    }

    public function createRules(SessionEntity $session): array
    {
        $requirements = $session->getVotingRequirements();

        return [
            new SessionStageRule($session),
            new UniqueVoteRule($this->votesRepository),
            new AgeRule($requirements->getMinAge()),
            new MaxVotesRule($this->votesRepository, $requirements->getMaxVotes()),
            new AddressRule(),
        ];
    }
}
