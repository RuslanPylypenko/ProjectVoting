<?php

namespace App\Application\Http\Project\Command\Submit;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\DTO\ProjectDTO;
use App\Domain\Project\Factory\ProjectFactory;
use App\Domain\User\Entity\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SubmitProjectHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProjectFactory $projectFactory,
    ) {
    }

    #[Route('/projects/submit', methods: ['POST'])]
    public function handler(SubmitProjectCommand $command, #[CurrentUser] ?UserEntity $userEntity, CityEntity $city): JsonResponse
    {
        $session = $city->getCurrentSession();

        try {
            $project = $this->projectFactory->create(
                command: $command,
                user: $userEntity,
                session: $session,
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
