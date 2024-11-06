<?php

namespace App\Domain\Project\Factory;

use App\Domain\Project\DTO\CreateProjectDTO;
use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Shared\Address\AddressLocator\AddressLocatorInterface;
use App\Domain\Shared\Enum\Category;
use App\Domain\User\Entity\UserEntity;

class ProjectFactory
{
    public function __construct(
        private AddressLocatorInterface $addressLocator,
    ) {
    }

    public function create(CreateProjectDTO $projectDTO, UserEntity $user, SessionEntity $session): ProjectEntity
    {
        $project = new ProjectEntity(
            $projectDTO->title,
            $projectDTO->description,
            Category::from($projectDTO->category),
            $this->addressLocator->findAddress([
                'street' => $projectDTO->street,
                'houseNumber' => $projectDTO->houseNumber,
            ]),
            $projectDTO->budget,
            $user,
            $session,
        );

        return $project;
    }
}
