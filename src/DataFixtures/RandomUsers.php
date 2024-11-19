<?php

namespace App\DataFixtures;

use App\Domain\City\Entity\CityEntity;
use App\Domain\User\Entity\UserEntity;
use Doctrine\Persistence\ObjectManager;

class RandomUsers
{
    /**
     * @return UserEntity[]
     */
    public function get(ObjectManager $manager, CityEntity $city, int $max): array
    {
        $query = $manager->createQueryBuilder()
            ->from(UserEntity::class, 'u')
            ->where('u.livingAddress.city = :livingCity')
            ->setParameter('livingCity', $city->getTitle());

        $totalUsers = (clone $query)
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $max = min($max, $totalUsers);

        return $query->select('u')->orderBy('RAND()')->setMaxResults($max)->getQuery()->getResult();
    }
}
