<?php

namespace App\Domain\Project;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Enum\ProjectStatus;
use App\Domain\Session\Enum\StageName;
use Doctrine\ORM\EntityManagerInterface;

class ProjectStatusUpdater
{
    public function __construct(
        private EntityManagerInterface $em,
        private WinnerDetector $winnerDetector,
    ) {
    }

    public function update(ProjectEntity $project, ?\DateTime $date = null): void
    {
        $date = $date ?? new \DateTime();
        $session = $project->getSession();

        match ($session->getActiveStage($date)->getName()) {
            StageName::VOTING => $this->processVotingStage($project, $date),
            StageName::WINNER => $this->processWinnerStage($project),
            default => null,
        };
    }

    private function processVotingStage(ProjectEntity $project, \DateTime $date): void
    {
        match (true) {
            $project->isApproved() => $project->setStatus(ProjectStatus::VOTING),
            $project->isPending() => $project->reject('The project was not reviewed by the moderator.', $date),
            $project->inReview() => $project->reject('Project did not pass moderation', $date),
            default => null,
        };

        $this->em->persist($project);
        $this->em->flush();
    }

    private function processWinnerStage(ProjectEntity $project): void
    {
        $isWinner = $this->winnerDetector->isWinner($project);
        match (true) {
            $isWinner => $project->winner(),
            !$isWinner && !$project->isNotWinner() => $project->notWinner(),
            default => null,
        };

        $this->em->persist($project);
        $this->em->flush();
    }
}
