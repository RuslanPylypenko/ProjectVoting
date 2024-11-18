<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Shared\Address\Address;
use App\Domain\User\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        if (null === $manager->getRepository(UserEntity::class)->findByEmail('app@email.test')) {
            $user = new UserEntity(
                $faker->firstName,
                'app@email.test',
                'password',
                new Address('Київ', 'Україна', 'вул. Центральна', '19422', '11'),
                new Address('Київ', 'Україна', 'вул. Центральна', '19422', '11'),
                $faker->dateTimeBetween('-50 years', '-10 years')
            );
            $manager->persist($user);
        }

        for ($i = 1; $i <= 100; ++$i) {
            $user = new UserEntity(
                $faker->firstName,
                random_int(111, 999) . $faker->boolean() ? $faker->email : $faker->freeEmail,
                'password',
                $address = new Address($faker->randomElement(['Київ', 'Львів']), 'Україна', 'вул. Центральна', '19422', '11'),
                $address,
                $faker->dateTimeBetween('-50 years', '-20 years')
            );
            $manager->persist($user);
        }

        $manager->flush();
    }
}
