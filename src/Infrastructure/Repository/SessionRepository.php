<?php

namespace App\Infrastructure\Repository;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SessionRepository extends ServiceEntityRepository
{
    public function findActiveSessions(): array
    {
        return [];
    }

    /**
     * @return ProjectEntity[]
     */
    public function findTopProjects(SessionEntity $session, int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'COUNT(v.id) AS voteCount')
            ->leftJoin('p.votes', 'v')
            ->where('p.session = :sessionId')
            ->setParameter('sessionId', $session->getId())
            ->groupBy('p.id')
            ->orderBy('voteCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}