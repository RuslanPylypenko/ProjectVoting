<?php

namespace App\Domain\Session;

use App\Domain\Session\Entity\SessionEntity;

interface SessionRepositoryInterface
{
    /**
     * @return SessionEntity[]
     */
    public function findActiveSessions(?\DateTime $date = null): array;
}