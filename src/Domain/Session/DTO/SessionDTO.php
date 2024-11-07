<?php

namespace App\Domain\Session\DTO;

use App\Domain\Session\Entity\SessionEntity;

class SessionDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $city,
        public array $winner_requirements,
        public array $voting_requirements,
        public array $submission_requirements,
    ) {
    }

    public static function fromEntity(SessionEntity $session): self
    {
        return new self(
            $session->getId(),
            $session->getName(),
            $session->getCity()->getTitle(),
            [
                'max_winners' => $session->getWinnerRequirements()->getMaxWinners(),
                'min_votes' => $session->getWinnerRequirements()->getMinVotes(),
            ],
            [
                'max_votes' => $session->getVotingRequirements()->getMaxVotes(),
                'min_age' => $session->getVotingRequirements()->getMinAge(),
                'only_residents' => $session->getVotingRequirements()->isOnlyResidents(),
            ],
            [
                'min_age' => $session->getSubmissionRequirements()->getMinAge(),
                'categories' => $session->getSubmissionRequirements()->getCategories(),
                'min_budget' => $session->getSubmissionRequirements()->getMinBudget()->getAmount(),
                'max_budget' => $session->getSubmissionRequirements()->getMaxBudget()->getAmount(),
                'only_residents' => $session->getSubmissionRequirements()->isOnlyResidents(),
            ]
        );
    }
}
