<?php

namespace App\Application\Home\DTO;

use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\Entity\Stage;

class SessionDTO
{
    public function __construct(
        public string $name,
        public array $stages,
    ) {
    }

    public static function fromEntity(SessionEntity $session): static
    {
        return new static(
            $session->getName(),
            $session->getStages()->map(static fn (Stage $stage) => [
                'name' => $stage->getName(),
                'start_date' => $stage->getStartDate()->format('Y-m-d'),
                'end_date' => $stage->getEndDate()->format('Y-m-d'),
                'is_active' => $stage->isActive(),
            ])->getValues(),
        );
    }
}
