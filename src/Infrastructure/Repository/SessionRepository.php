<?php

namespace App\Infrastructure\Repository;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionEntity>
 */

class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionEntity::class);
    }

    public function findActiveSessions(): array
    {
        return [];
    }

    public function findSessionByPeriod(\DateTime $getStartDate, \DateTime $getEndDate): ?SessionEntity
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->join('s.stages', 'ss')
            ->groupBy('s.id')
            ->having('MIN(ss.startDate) <= :start_date')
            ->orHaving('MAX(ss.endDate) >= :end_date')
            ->setParameter('start_date', $getStartDate)
            ->setParameter('end_date', $getEndDate)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
