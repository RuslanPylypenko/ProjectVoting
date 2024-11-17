<?php

namespace App\Domain\Project;

use App\Domain\Project\Entity\ProjectEntity;
use App\Infrastructure\Repository\ProjectsRepository;
use App\Infrastructure\Repository\SessionRepository;

class WinnerDetector
{
    public function __construct(private ProjectsRepository $projectsRepository)
    {
    }

    public function isWinner(ProjectEntity $project): bool
    {
        $session = $project->getSession();

        if ($project->getVotes()->count() < $session->getWinnerRequirements()->getMinVotes()) {
            return false;
        }

        $topProjects = $this->projectsRepository->getTopProjectsQuery($session, $session->getWinnerRequirements()->getMaxWinners())->getResult();

        foreach ($topProjects as $projectInfo) {
            if ($project->getId() === $projectInfo->getId()) {
                return true;
            }
        }

        return false;
    }
}
