<?php

namespace App\DataFixtures;

use App\Domain\Session\Entity\SessionEntity;
use App\Domain\User\Entity\UserEntity;
use Doctrine\Persistence\ObjectManager;

class RandomUsers
{
    /**
     * @return UserEntity[]
     */
    public function get(ObjectManager $manager, SessionEntity $session, int $limit): array
    {
        $query = $manager->createQueryBuilder()
            ->select('u')
            ->from(UserEntity::class, 'u')
            ->where('u.livingAddress.city = :livingCity')
            ->setParameter('livingCity', $session->getCity()->getTitle())
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}