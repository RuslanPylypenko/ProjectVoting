<?php

namespace App\User\Repository;

use App\User\Entity\UserEntity;
use App\User\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    public function findByEmail(string $email): ?UserEntity
    {
        try {
            return $this->getByEmail($email);
        } catch (UserNotFoundException) {
            return null;
        }
    }

    public function getByEmail(string $email): UserEntity
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}