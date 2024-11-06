<?php

namespace App\Domain\Session\Entity\Requirement;

use App\Domain\Session\Entity\SessionEntity;
use App\Infrastructure\Doctrine\Type\MoneyType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

#[ORM\Entity]
#[ORM\Table(name: 'submission_requirements')]
class SubmissionRequirements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: SessionEntity::class, inversedBy: 'submissionRequirements')]
    #[ORM\JoinColumn(name: 'session_id', referencedColumnName: 'id', nullable: false)]
    private SessionEntity $session;

    #[ORM\Column(name: 'min_age', type: Types::INTEGER)]
    private int $minAge;

    #[ORM\Column(name: 'categories', type: Types::JSON, nullable: true)]
    private array $categories = [];

    #[ORM\Column(name: 'max_budget', type: MoneyType::MONEY)]
    private Money $maxBudget;

    #[ORM\Column(name: 'min_budget', type: MoneyType::MONEY)]
    private Money $minBudget;

    #[ORM\Column(name: 'only_residents', type: Types::BOOLEAN)]
    private bool $onlyResidents;

    public function __construct(
        int $minAge,
        array $categories,
        Money $minBudget,
        Money $maxBudget,
        bool $onlyResidents,
    ) {
        $this->categories = $categories;
        $this->maxBudget = $maxBudget;
        $this->minAge = $minAge;
        $this->minBudget = $minBudget;
        $this->onlyResidents = $onlyResidents;
    }

    public function getMinAge(): int
    {
        return $this->minAge;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getMaxBudget(): Money
    {
        return $this->maxBudget;
    }

    public function getMinBudget(): Money
    {
        return $this->minBudget;
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
