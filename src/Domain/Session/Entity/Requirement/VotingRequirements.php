<?php

namespace App\Domain\Session\Entity\Requirement;

use App\Domain\Session\Entity\SessionEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'voting_requirements')]
class VotingRequirements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: SessionEntity::class, inversedBy: 'votingRequirements')]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false)]
    private SessionEntity $session;

    #[ORM\Column(name: 'min_age', type: Types::INTEGER)]
    private int $minAge;

    #[ORM\Column(name: 'max_votes', type: Types::INTEGER)]
    private int $maxVotes;

    #[ORM\Column(name: 'only_residents', type: Types::BOOLEAN)]
    private bool $onlyResidents;

    public function __construct(
        int $maxVotes,
        bool $onlyResidents,
        int $minAge,
    ) {
        $this->maxVotes = $maxVotes;
        $this->minAge = $minAge;
        $this->onlyResidents = $onlyResidents;
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

    public function setSession(SessionEntity $session): void
    {
        $this->session = $session;
    }
}
