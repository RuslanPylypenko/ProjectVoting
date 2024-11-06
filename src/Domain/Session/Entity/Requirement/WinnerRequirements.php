<?php

namespace App\Domain\Session\Entity\Requirement;

use App\Domain\Session\Entity\SessionEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'winner_requirements')]
class WinnerRequirements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: SessionEntity::class, inversedBy: 'winnerRequirements')]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false)]
    private SessionEntity $session;

    #[ORM\Column(name: 'max_winners', type: Types::INTEGER)]
    private int $maxWinners;

    #[ORM\Column(name: 'min_votes', type: Types::INTEGER)]
    private int $minVotes;

    public function __construct(
        int $maxWinners,
        int $minVotes,
    ) {
        $this->maxWinners = $maxWinners;
        $this->minVotes = $minVotes;
    }

    public function getMaxWinners(): int
    {
        return $this->maxWinners;
    }

    public function getMinVotes(): int
    {
        return $this->minVotes;
    }

    public function setSession(SessionEntity $session): void
    {
        $this->session = $session;
    }
}
