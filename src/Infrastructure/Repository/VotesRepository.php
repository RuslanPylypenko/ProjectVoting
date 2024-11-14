<?php

namespace App\Infrastructure\Repository;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\User\Entity\UserEntity;
use App\Domain\Vote\Entity\VoteEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VoteEntity>
 */
class VotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteEntity::class);
    }

    public function findUserVote(ProjectEntity $project, UserEntity $user): ?VoteEntity
    {
        $qb = $this->createQueryBuilder('v');

        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('v.project', ':projectId'),
                    $qb->expr()->eq('v.user', ':userId')
                )
            )
            ->setParameter('projectId', $project->getId())
            ->setParameter('userId', $user->getId())
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCountVotes(UserEntity $user, SessionEntity $session): int
    {
        $qb = $this->createQueryBuilder('v');

        return $qb->select('COUNT(v.id)')
            ->leftJoin('v.project', 'p')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('v.user', ':userId'),
                    $qb->expr()->eq('p.session', ':sessionId')
                )
            )
            ->setParameter('sessionId', $session->getId())
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
