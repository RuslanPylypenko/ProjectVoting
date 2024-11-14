<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Project\Exception\VoteRuleValidationException;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Vote\Entity\VoteEntity;

class SessionStageRule implements VotingRuleInterface
{
    public function __construct(private SessionEntity $session, private ?\DateTime $voteDate = null)
    {
    }

    public function validate(VoteEntity $vote): void
    {
        $date = $this->voteDate ?? new \DateTime();
        $stage = $this->session->getActiveStage($date);

        if (!$stage->isVoting()) {
            throw new VoteRuleValidationException(sprintf('User can only vote during the voting stage. The current stage is: %s.', $this->session->getActiveStage()->getName()->value));
        }
    }
}
