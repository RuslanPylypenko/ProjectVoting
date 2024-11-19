<?php

namespace App\DataFixtures;

use App\Domain\Vote\Factory\VoteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectVoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private VoteFactory $voteFactory, private RandomUsers $randomUsers)
    {
    }

    public function load(ObjectManager $manager): void
    {
        ini_set('memory_limit', '8G');

        $faker = Factory::create();
        $connection = $manager->getConnection();

        $projectIds = $connection->fetchFirstColumn('SELECT id FROM projects');
        $userIds = $connection->fetchFirstColumn('SELECT id FROM users');

        $batchSize = 1_000;
        $sql = 'INSERT IGNORE INTO project_votes (project_id, user_id, created_at) VALUES ';

        for ($batch = 0; $batch < 5_000; ++$batch) {
            $values = [];

            for ($i = 0; $i < $batchSize; ++$i) {
                $projectId = $faker->randomElement($projectIds);
                $userId = $faker->randomElement($userIds);

                $values[] = sprintf(
                    '(%d, %d, "%s")',
                    $projectId,
                    $userId,
                    $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')
                );
            }

            $connection->executeQuery($sql.implode(', ', $values));
        }
    }


    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
