<?php

namespace App\Infrastructure\Repository;

use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\SessionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionEntity>
 */
class SessionRepository extends ServiceEntityRepository implements SessionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionEntity::class);
    }

    /**
     * @return SessionEntity[]
     */
    public function findActiveSessions(?\DateTime $date = null): array
    {
        $date = $date ?? new \DateTime();

        return $this->createQueryBuilder('s')
            ->select('s')
            ->join('s.stages', 'ss')
            ->groupBy('s.id')
            ->having('MIN(ss.startDate) <= :start_date')
            ->orHaving('MAX(ss.endDate) >= :end_date')
            ->setParameter('start_date', $date)
            ->setParameter('end_date', $date)
            ->getQuery()
            ->getResult();
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
