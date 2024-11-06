<?php

namespace App\Application\Project\Command\Approve;

use App\Domain\Project\Entity\ProjectEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApproveProjectHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/project/approve/{id}', methods: ['PATCH'])]
    public function handle(ProjectEntity $projectEntity): Response
    {
        $projectEntity->approve();

        $this->em->persist($projectEntity);
        $this->em->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
