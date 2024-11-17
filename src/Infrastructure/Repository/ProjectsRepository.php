<?php

namespace App\Infrastructure\Repository;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\User\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function list(): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->setMaxResults(100);

        return $qb->getQuery()->getArrayResult();
    }

    public function getRandomProjects(SessionEntity $session, $limit = 10): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.session = :sessionId')
            ->select('p AS project', 'COUNT(v.id) AS votes')
            ->leftJoin('p.votes', 'v')
            ->groupBy('p.id')
            ->setParameter('sessionId', $session->getId())
            ->orderBy('RAND()')
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();
    }

    public function getTopProjectsArray(SessionEntity $session, int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->select('p AS project', 'COUNT(v.id) AS votes')
            ->leftJoin('p.votes', 'v')
            ->where('p.session = :sessionId')
            ->setParameter('sessionId', $session->getId())
            ->groupBy('p.id')
            ->orderBy('COUNT(v.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getArrayResult();
    }

    /**
     * @return ProjectEntity[]
     */
    public function getTopProjects(SessionEntity $session, int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->select('p AS project', 'COUNT(v.id) AS votes')
            ->leftJoin('p.votes', 'v')
            ->where('p.session = :sessionId')
            ->setParameter('sessionId', $session->getId())
            ->groupBy('p.id')
            ->orderBy('COUNT(v.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
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
