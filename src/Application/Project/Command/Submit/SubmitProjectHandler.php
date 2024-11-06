<?php

namespace App\Application\Project\Command\Submit;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\DTO\CreateProjectDTO;
use App\Domain\Project\DTO\ProjectDTO;
use App\Domain\Project\Factory\ProjectFactory;
use App\Domain\Project\Validator\ProjectValidator;
use App\Domain\Project\Validator\Rule\RuleFactory;
use App\Domain\User\Entity\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SubmitProjectHandler extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProjectFactory $projectFactory,
        private RuleFactory $ruleFactory,
        private ProjectValidator $projectValidator,
    ) {
    }

    #[Route('/projects/create', methods: ['POST'])]
    public function handler(SubmitProjectCommand $command, #[CurrentUser] ?UserEntity $userEntity, CityEntity $city): JsonResponse
    {
        $session = $city->getCurrentSession();

        try {
            $project = $this->projectFactory->create(
                projectDTO: CreateProjectDTO::fromCommand($command),
                user: $userEntity,
                session: $session,
            );

            $this->projectValidator->validate(
                $project,
                $this->ruleFactory->createRulesForSubmission($session)
            );

            $this->em->persist($project);
            $this->em->flush();

            return new JsonResponse([
                'project' => ProjectDTO::fromEntity($project),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
