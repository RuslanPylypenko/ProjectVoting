<?php

namespace App\Domain\Project\Factory;

use App\Application\Project\Command\Submit\SubmitProjectCommand;
use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Project\Entity\ProjectHistoryEntity;
use App\Domain\Project\Validator\ProjectValidator;
use App\Domain\Project\Validator\Rule\RuleFactory;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Shared\Address\AddressLocator\AddressLocatorInterface;
use App\Domain\Shared\Enum\Category;
use App\Domain\User\Entity\UserEntity;
use Money\Money;

class ProjectFactory
{
    public function __construct(
        private AddressLocatorInterface $addressLocator,
        private RuleFactory $ruleFactory,
        private ProjectValidator $projectValidator,
    ) {
    }

    public function create(SubmitProjectCommand $command, UserEntity $user, SessionEntity $session, ?\DateTime $submissionDate = null): ProjectEntity
    {
        $project = new ProjectEntity(
            $command->title,
            $command->description,
            Category::from($command->category),
            $this->addressLocator->findAddress([
                'city' => $session->getCity()->getTitle(),
                'street' => $command->street,
                'houseNumber' => $command->houseNumber,
            ]),
            Money::USD($command->budget),
            $user,
            $session,
            $submissionDate
        );

        $this->projectValidator->validate(
            $project,
            $this->ruleFactory->createRulesForSubmission($session)
        );

        $project->addHistory(ProjectHistoryEntity::createAction($user));

        return $project;
    }
}
