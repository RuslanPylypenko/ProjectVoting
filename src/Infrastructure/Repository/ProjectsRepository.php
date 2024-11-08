<?php

namespace App\Infrastructure\Repository;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\User\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectEntity>
 */
class ProjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectEntity::class);
    }

    public function findById(int $id): ?ProjectEntity
    {
        try {
            return $this->find($id);
        } catch (UserNotFoundException) {
            return null;
        }
    }

    public function getBySession(SessionEntity $session): array
    {
        return [];
    }

    public function findVoting(SessionEntity $session): array
    {
        return [];
    }
}
