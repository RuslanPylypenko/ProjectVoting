<?php

namespace App\Domain\Session\Factory;

use App\Application\Http\Session\Command\Create\CreateSessionCommand;
use App\Domain\City\Entity\CityEntity;
use App\Domain\Session\Entity\Requirement\SubmissionRequirements;
use App\Domain\Session\Entity\Requirement\VotingRequirements;
use App\Domain\Session\Entity\Requirement\WinnerRequirements;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\Entity\Stage;
use App\Domain\Session\Enum\StageName;
use App\Domain\Session\Validator\StagesValidator;
use Money\Money;

class SessionFactory
{
    public function __construct(private StagesValidator $stagesValidator)
    {
    }

    public function create(CreateSessionCommand $command, CityEntity $city): SessionEntity
    {
        $submissionRequirements = new SubmissionRequirements(
            minAge: $command->submissionRequirements->minAge,
            categories: $command->submissionRequirements->categories,
            minBudget: Money::USD($command->submissionRequirements->minBudget),
            maxBudget: Money::USD($command->submissionRequirements->maxBudget),
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

        $stages = array_map(static fn ($stage) => new Stage(
            StageName::from($stage['name']),
            new \DateTime($stage['start_date']),
            new \DateTime($stage['end_date']),
        ), $command->stages);

        $this->stagesValidator->validate($stages);

        return new SessionEntity(
            $command->name,
            $city,
            $stages,
            $winnerRequirements,
            $votingRequirements,
            $submissionRequirements
        );
    }
}
