<?php

namespace App\Domain\Session\Entity;

use App\Domain\Session\Enum\StageName;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'session_stages')]
#[ORM\UniqueConstraint(name: 'unique_name_session', columns: ['name', 'session_id'])]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 32, enumType: StageName::class)]
    private StageName $name;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $startDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $endDate;

    #[ORM\ManyToOne(targetEntity: SessionEntity::class, cascade: ['persist'], inversedBy: 'stages')]
    #[ORM\JoinColumn(name: 'session_id', nullable: false)]
    private ?SessionEntity $session = null;

    public function __construct(StageName $name, \DateTime $startDate, \DateTime $endDate)
    {
        if ($startDate > $endDate) {
            throw new \InvalidArgumentException(sprintf('Invalid date range for stage "%s": Start date "%s" cannot be greater than end date "%s".', $name->value, $startDate->format('Y-m-d'), $endDate->format('Y-m-d')));
        }

        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getName(): StageName
    {
        return $this->name;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function isSubmission(): bool
    {
        return StageName::SUBMISSION === $this->name;
    }

    public function isVoting(): bool
    {
        return StageName::VOTING === $this->name;
    }

    public function isActive(?\DateTime $date = null): bool
    {
        $date = $date ?? new \DateTime();
        return $this->startDate <= $date && $this->endDate >= $date;
    }

    public function getSession(): ?SessionEntity
    {
        return $this->session;
    }

    public function setSession(?SessionEntity $session): self
    {
        $this->session = $session;

        return $this;
    }
}
