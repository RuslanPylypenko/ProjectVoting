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
        $faker = Factory::create('uk_UA');

        $offset = 0;
        $batchSize = 1000;

        //  $manager->beginTransaction();

        try {
            do {
                /** @var SessionEntity[] $sessions */
                $sessions = $manager->getRepository(SessionEntity::class)
                    ->createQueryBuilder('e')
                    ->setMaxResults($batchSize)
                    ->setFirstResult($offset)
                    ->getQuery()
                    ->getResult();

                $totalUsers = $manager->createQueryBuilder()
                    ->select('count(u.id)')
                    ->from(UserEntity::class, 'u')
                    ->getQuery()
                    ->getSingleScalarResult();

                foreach ($sessions as $session) {
                    $submissionRequirements = $session->getSubmissionRequirements();

                    $users = $this->getRandomUsers($manager, $totalUsers, $session);

                    $categories = $submissionRequirements->getCategories();

                    for ($i = 1; $i <= 5; ++$i) {
                        $command = new SubmitProjectCommand();
                        $command->title = $faker->words(5, true);
                        $command->budget = $faker->randomFloat(nbMaxDecimals: 2, min: $submissionRequirements->getMinBudget()->getAmount(), max: $submissionRequirements->getMaxBudget()->getAmount());
                        $command->category = $faker->randomElement($categories);
                        $command->houseNumber = $faker->randomNumber();
                        $command->street = 'вул. '.$faker->streetName;
                        $command->description = $faker->realText(random_int(300, 1000));

                        $project = $this->projectFactory->create($command, $faker->randomElement($users), $session);

                        $manager->persist($project);
                    }
                }

                $offset += $batchSize;
            } while (count($sessions) > 0);

            $manager->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return UserEntity[]
     */
    public function getRandomUsers(ObjectManager $manager, int $totalUsers, SessionEntity $session): array
    {
        $limit = 100;
        $randomOffset = rand(0, max(0, $totalUsers - $limit));

        $query = $manager->createQueryBuilder()
            ->select('u')
            ->from(UserEntity::class, 'u')
            ->where('u.livingAddress.city = :livingCity')
            ->setParameter('livingCity', $session->getCity()->getTitle())
            ->setFirstResult($randomOffset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
            UserFixtures::class,
        ];
    }
}
