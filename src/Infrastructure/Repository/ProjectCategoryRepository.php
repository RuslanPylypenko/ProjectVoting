<?php

namespace App\Infrastructure\Repository;

use App\Domain\Project\Entity\CategoryEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryEntity>
 */
class ProjectCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryEntity::class);
    }

    public function findCategories(array $ids): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}
