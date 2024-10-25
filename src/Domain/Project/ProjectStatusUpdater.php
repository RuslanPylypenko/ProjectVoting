<?php

namespace App\Domain\Project;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Enum\ProjectStatus;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\Enum\StageName;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ProjectStatusUpdater
{
    public function __construct(
        private EntityManagerInterface $em,
        private WinnerDetector $winnerDetector,
    ) {
    }

    public function run(SessionEntity $session, DateTime $day): void
    {
        match ($session->getActiveStage($day)->getName()) {
            StageName::VOTING => $this->processVotingStage($session, $day),
            StageName::WINNER => $this->processWinnerStage($session),
        };
    }

    private function processVotingStage(SessionEntity $session, DateTime $day): void
    {
        $projects = $session->getProjects();

        foreach ($projects as $project) {
            match (true) {
                $project->isApproved() => $project->setStatus(ProjectStatus::VOTING),
                $project->isPending() => $project->reject('The project was not reviewed by the moderator.', $day),
                $project->inReview() => $project->reject('Project did not pass moderation', $day),
            };

            $this->em->persist($project);
        }
        $this->em->flush();
    }

    private function processWinnerStage(SessionEntity $session): void
    {
        $votingProjects = $session->getProjects()->filter(static fn(ProjectEntity $p) => $p->isVoting());

        /** @var ProjectEntity $project */
        foreach ($votingProjects as $project) {
            $this->winnerDetector->isWinner($project) ? $project->winner() : $project->notWinner();
            $this->em->persist($project);
        }
        $this->em->flush();
    }
}