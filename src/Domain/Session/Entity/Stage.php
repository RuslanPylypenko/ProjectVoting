<?php

namespace App\Domain\Session\Entity;

use App\Domain\Session\Enum\StageName;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('session_stages')]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 32, enumType: StageName::class)]
    private StageName $name;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $startDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $endDate;

    public function __construct(StageName $name, DateTime $startDate, DateTime $endDate)
    {
        if ($startDate > $endDate) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid date range for stage "%s": Start date "%s" cannot be greater than end date "%s".',
                $name->value,
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ));
        }

        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getName(): StageName
    {
        return $this->name;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function isSubmission(): bool
    {
        return $this->name === StageName::SUBMISSION;
    }

    public function isActive(DateTime $date): bool
    {
        return $this->startDate <= $date || $this->endDate >= $date;
    }
}