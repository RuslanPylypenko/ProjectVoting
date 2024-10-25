<?php

namespace App\Domain\Session\Entity\Requirement;

use App\Domain\Session\Entity\SessionEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'voting_requirements')]
class VotingRequirements extends BaseSessionRequirementEntity
{
    #[ORM\Column(name: 'min_age', type: Types::INTEGER)]
    private int $minAge;

    #[ORM\Column(name: 'max_votes', type: Types::INTEGER)]
    private int $maxVotes;

    #[ORM\Column(name: 'only_residents', type: Types::BOOLEAN)]
    private bool $onlyResidents;

    public function __construct(
        string $maxVotes,
        string $onlyResidents,
        int $minAge,
        SessionEntity $session,
    )
    {
        $this->maxVotes = $maxVotes;
        $this->minAge = $minAge;
        $this->onlyResidents = $onlyResidents;

        parent::__construct($session);
    }

    public function getMinAge(): int
    {
        return $this->minAge;
    }

    public function getMaxVotes(): int
    {
        return $this->maxVotes;
    }

    public function isOnlyResidents(): bool
    {
        return $this->onlyResidents;
    }
}