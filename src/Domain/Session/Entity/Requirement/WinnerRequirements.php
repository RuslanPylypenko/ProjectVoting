<?php

namespace App\Domain\Session\Entity\Requirement;

use App\Domain\Session\Entity\SessionEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'winner_requirements')]
class WinnerRequirements extends BaseSessionRequirementEntity
{
    #[ORM\Column(name: 'max_winners', type: Types::INTEGER)]
    private int $maxWinners;

    #[ORM\Column(name: 'min_votes', type: Types::INTEGER)]
    private int $minVotes;

    public function __construct(
        int $maxWinners,
        int $minVotes,
        SessionEntity $session,
    ) {
        $this->maxWinners = $maxWinners;
        $this->minVotes = $minVotes;
        parent::__construct($session);
    }

    public function getMaxWinners(): int
    {
        return $this->maxWinners;
    }

    public function getMinVotes(): int
    {
        return $this->minVotes;
    }

}