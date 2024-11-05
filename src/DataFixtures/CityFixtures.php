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

        $city = new CityEntity(
            "Львів",
            'lviv',
            new Address('Львів', 'Україна')
        );
        $manager->persist($city);

        $city = new CityEntity(
            "Київ",
            'kyiv',
            new Address('Київ', 'Україна'),
        );

        $manager->persist($city);

        $manager->flush();
    }
}
