<?php

namespace App\Domain\Project;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;

interface ProjectRepositoryInterface
{
    /**
     * @return ProjectEntity[]
     */
    public function getTopProjects(SessionEntity $session, int $limit = 10): array;
}