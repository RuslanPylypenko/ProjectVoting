<?php

namespace App\Domain\Vote\Validator\Rule;

use App\Domain\Project\Exception\VoteRuleValidationException;
use App\Domain\Vote\Entity\VoteEntity;

class AddressRule implements VotingRuleInterface
{
    public function validate(VoteEntity $vote): void
    {
        if (!$vote->getProject()->getSession()->getVotingRequirements()->isOnlyResidents()) {
            return;
        }
        $requiredAddress = $vote->getProject()->getAddress();
        $livingAddress = $vote->getUser()->getLivingAddress();
        $registrationAddress = $vote->getUser()->getRegistrationAddress();

        if (!$livingAddress->isSameCity($registrationAddress) || !$registrationAddress->isSameCity($requiredAddress)) {
            throw new VoteRuleValidationException('Project address does not match the required address criteria.');
        }
    }
}
