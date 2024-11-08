<?php

namespace App\DataFixtures;

use App\Application\Project\Command\Submit\SubmitProjectCommand;
use App\Domain\Project\Factory\ProjectFactory;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\User\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private ProjectFactory $projectFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        gc_collect_cycles();

        $faker = Factory::create('uk_UA');

        $offset = 0;
        $batchSize = 1000;

        do {
            /** @var SessionEntity[] $sessions */
            $sessions = $manager->getRepository(SessionEntity::class)
                ->createQueryBuilder('e')
                ->setMaxResults($batchSize)
                ->setFirstResult($offset)
                ->getQuery()
                ->getResult();

            foreach ($sessions as $session) {
                $submissionRequirements = $session->getSubmissionRequirements();

                for ($i = 1; $i <= random_int(30, 50); ++$i) {
                    $command = new SubmitProjectCommand();
                    $command->title = $faker->words(5, true);
                    $command->budget = $faker->randomFloat(nbMaxDecimals: 2, min: $submissionRequirements->getMinBudget()->getAmount(), max: $submissionRequirements->getMaxBudget()->getAmount());
                    $command->category = $faker->randomElement($submissionRequirements->getCategories());
                    $command->houseNumber = $faker->randomNumber();
                    $command->street = 'вул. '.$faker->streetName;
                    $command->description = $faker->realText(random_int(300, 1000));

                    $project = $this->projectFactory->create($command, $this->getRandomUser($manager, $session), $session);

                    $manager->persist($project);
                }
                $manager->flush();
            }

            $offset += $batchSize;
            $manager->clear();
        } while (count($sessions) > 0);
    }

    public function getRandomUser(ObjectManager $manager, SessionEntity $session): UserEntity
    {
        $connection = $manager->getConnection();
        $sql = 'SELECT id FROM users WHERE living_address_city =:city ORDER BY RAND() LIMIT 1';
        $stmt = $connection->executeQuery($sql, ['city' => $session->getCity()->getTitle()]);
        $result = $stmt->fetchAssociative();

        return $manager->getRepository(UserEntity::class)->find($result['id']);
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
            UserFixtures::class,
        ];
    }
}
