<?php

namespace App\Application\Console\Project;

use App\Domain\Project\ProjectStatusUpdater;
use App\Domain\Session\SessionRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-project-statuses',
    description: 'Update project statuses',
)]
class UpdateProjectStatusesCommand extends Command
{
    public function __construct(
        private ProjectStatusUpdater $projectStatusUpdater,
        private SessionRepositoryInterface $sessionRepository,
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $sessions = $this->sessionRepository->findActiveSessions();

            foreach ($sessions as $session) {
                foreach ($session->getProjects() as $project) {
                    $this->projectStatusUpdater->update($project, new \DateTime());
                }
            }

            $output->writeln('Project status updated successfully.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }
}
