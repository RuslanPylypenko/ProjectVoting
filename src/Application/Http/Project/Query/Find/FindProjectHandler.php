<?php

namespace App\Application\Http\Project\Query\Find;

use App\Application\Http\Project\DTO\ProjectCardDTO;
use App\Infrastructure\Repository\ProjectsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FindProjectHandler
{
    public function __construct(
        private ProjectsRepository $projectsRepository,
    ) {
    }

    #[Route('/projects', methods: ['GET'])]
    public function handle(): JsonResponse
    {
        return new JsonResponse([
            'projects' => array_map(fn (array $row) => ProjectCardDTO::createFromArray($row), $this->projectsRepository->list()),
        ]);
    }
}
