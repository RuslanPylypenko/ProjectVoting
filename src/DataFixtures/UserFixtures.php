<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Shared\Address\Address;
use App\Domain\User\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ini_set('memory_limit', '8G');

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
            $manager->flush();
        }

        $password = password_hash('password', PASSWORD_BCRYPT);

        /** @var CityEntity[] $cities */
        $cities = $manager->getRepository(CityEntity::class)
            ->createQueryBuilder('c')
            ->getQuery()
            ->toIterable();

        $connection = $manager->getConnection();

        $batchSize = 10_000; // Number of rows per batch
        $sql = 'INSERT INTO users (
                name, 
                email, 
                birth_date, 
                password_hash,
                created_at,
                updated_at,
                living_address_house_number, 
                living_address_street, 
                living_address_postal_code, 
                living_address_city, 
                living_address_country,  
                registration_address_house_number, 
                registration_address_street, 
                registration_address_postal_code, 
                registration_address_city, 
                registration_address_country
            ) VALUES ';

        $connection->beginTransaction();

        try {
            foreach ($cities as $city) {
                $values = [];
                $placeholders = [];

                for ($i = 1; $i <= mt_rand(20_000, 200_000); ++$i) {
                    $values[] = $faker->firstName;
                    $values[] = mt_rand(1111, 9999).($faker->boolean() ? $faker->email : $faker->freeEmail);
                    $values[] = $faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d H:i:s');
                    $values[] = $password;
                    $values[] = $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
                    $values[] = $createdAt;
                    $values[] = null; // living_address_house_number
                    $values[] = null; // living_address_street
                    $values[] = null; // living_address_postal_code
                    $values[] = $city->getTitle(); // living_address_city
                    $values[] = $city->getAddress()->getCountry(); // living_address_country
                    $values[] = null; // registration_address_house_number
                    $values[] = null; // registration_address_street
                    $values[] = null; // registration_address_postal_code
                    $values[] = $city->getTitle(); // registration_address_city
                    $values[] = $city->getAddress()->getCountry(); // registration_address_country

                    $placeholders[] = '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

                    // Execute in batches
                    if (0 === $i % $batchSize) {
                        $connection->executeQuery($sql.implode(', ', $placeholders), $values);
                        $values = [];
                        $placeholders = [];
                    }
                }

                // Insert remaining rows
                if (!empty($values)) {
                    $connection->executeQuery($sql.implode(', ', $placeholders), $values);
                }
            }

            $connection->commit();
        } catch (\Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }
}
