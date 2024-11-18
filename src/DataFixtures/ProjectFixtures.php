<?php

namespace App\DataFixtures;

use App\Application\Http\Project\Command\Submit\SubmitProjectCommand;
use App\Domain\Project\Factory\ProjectFactory;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\Entity\Stage;
use App\Domain\User\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private ProjectFactory $projectFactory, private RandomUsers $randomUsers)
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

                    $users = $this->randomUsers->get($manager, $session, random_int(100, $totalUsers));

                    $categories = $submissionRequirements->getCategories();

                    for ($i = 1; $i <= random_int(20, 100); ++$i) {
                        $command = new SubmitProjectCommand();
                        $command->title = $faker->words(5, true);
                        $command->budget = $faker->randomFloat(nbMaxDecimals: 2, min: $submissionRequirements->getMinBudget()->getAmount(), max: $submissionRequirements->getMaxBudget()->getAmount());
                        $command->category = $faker->randomElement($categories);
                        $command->houseNumber = $faker->randomNumber();
                        $command->street = 'вул. '.$faker->streetName;
                        $command->description = $faker->realText(random_int(300, 1000));

                        $submission = $session->getStages()->findFirst(fn (string $value, Stage $stage) => $stage->isSubmission());

                        $project = $this->projectFactory->create($command, $faker->randomElement($users), $session, $submission->getStartDate());

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

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
            UserFixtures::class,
        ];
    }
}
