<?php

namespace App\Application\Http\Home\Query;

use App\Application\Http\Home\DTO\CityDto;
use App\Application\Http\Home\DTO\SessionDTO;
use App\Application\Http\Project\DTO\ProjectCardDTO;
use App\Domain\City\Entity\CityEntity;
use App\Infrastructure\Repository\ProjectsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomePageHandler
{
    public function __construct(
        private ProjectsRepository $projectsRepository,
    ) {
    }

    #[Route('/home', methods: ['GET'])]
    public function handle(CityEntity $city): JsonResponse
    {
        return new JsonResponse([
            'city' => CityDto::fromEntity($city),
            'session' => SessionDTO::fromEntity($session = $city->getCurrentSession()),
            'random_projects' => array_map(fn (array $row) => ProjectCardDTO::createFromArray($row), $this->projectsRepository->getRandomProjects($session)),
            'top_projects' => array_map(fn (array $row) => ProjectCardDTO::createFromArray($row), $this->projectsRepository->getTopProjectsArray($session)),
        ]);
    }
}
