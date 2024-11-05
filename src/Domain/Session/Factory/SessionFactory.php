<?php

namespace App\Domain\Session\Factory;

use App\Application\Session\Command\Create\CreateSessionCommand;
use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\Entity\CategoryEntity;
use App\Domain\Session\Entity\Requirement\SubmissionRequirements;
use App\Domain\Session\Entity\Requirement\VotingRequirements;
use App\Domain\Session\Entity\Requirement\WinnerRequirements;
use App\Domain\Session\Entity\SessionEntity;
use App\Infrastructure\Repository\ProjectCategoryRepository;
use Money\Money;

class SessionFactory
{
    public function __construct(
        private ProjectCategoryRepository $categoryRepository,
    )
    {
    }

    public function create(CreateSessionCommand $command, CityEntity $city): SessionEntity
    {
        $categories = $this->categoryRepository->findCategories($command->submissionRequirements->categories);

        if (empty($categories)) {
            throw new \InvalidArgumentException('There are no categories available');
        }

        $submissionRequirements = new SubmissionRequirements(
            minAge: $command->submissionRequirements->minAge,
            categories: array_map(fn(CategoryEntity $category) => $category->getId(), $categories),
            minBudget: Money::USD($command->submissionRequirements->minBudget),
            maxBudget:  Money::USD($command->submissionRequirements->maxBudget),
            onlyResidents: $command->submissionRequirements->onlyResidents,
        );

        $votingRequirements = new VotingRequirements(
            maxVotes: $command->votingRequirements->maxVotes,
            onlyResidents: $command->votingRequirements->onlyResidents,
            minAge: $command->votingRequirements->minAge
        );

        $winnerRequirements = new WinnerRequirements(
            maxWinners: $command->winnerRequirements->maxWinners,
            minVotes: $command->winnerRequirements->minVotes,
        );

        return new SessionEntity(
            $command->name,
            $city,
            $winnerRequirements,
            $votingRequirements,
            $submissionRequirements
        );
    }
}