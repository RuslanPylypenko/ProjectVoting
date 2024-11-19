<?php

namespace App\DataFixtures;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Shared\Address\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        if (null === $manager->getRepository(CityEntity::class)->findOneBy(['slug' => 'kyiv'])) {
            $city = new CityEntity(
                'Київ',
                'kyiv',
                new Address('Київ', 'Україна'),
            );
            $manager->persist($city);
        }

        for ($i = 0; $i < 5; ++$i) {
            $city = new CityEntity(
                $city = $faker->city,
                $city,
                new Address($city, 'Україна'),
            );
            $manager->persist($city);
        }

        $manager->flush();
    }
}
