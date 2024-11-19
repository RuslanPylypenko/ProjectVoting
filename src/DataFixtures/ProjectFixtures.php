<?php

namespace App\DataFixtures;

use App\Application\Http\Project\Command\Submit\SubmitProjectCommand;
use App\Domain\Project\Factory\ProjectFactory;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\Entity\Stage;
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
        ini_set('memory_limit', '1024M');

        $faker = Factory::create('uk_UA');

        /** @var SessionEntity[] $sessions */
        $sessions = $manager->getRepository(SessionEntity::class)
            ->createQueryBuilder('e')
            ->getQuery()
            ->toIterable();

        foreach ($sessions as $session) {
            $submissionRequirements = $session->getSubmissionRequirements();
            $users = $this->randomUsers->get($manager, $session->getCity(), 1000);
            $categories = $submissionRequirements->getCategories();

            for ($i = 1; $i <= mt_rand(50, 150); ++$i) {
                $command = new SubmitProjectCommand();
                $command->title = $faker->words(5, true);
                $command->budget = $faker->randomFloat(nbMaxDecimals: 2, min: $submissionRequirements->getMinBudget()->getAmount(), max: $submissionRequirements->getMaxBudget()->getAmount());
                $command->category = $faker->randomElement($categories);
                $command->houseNumber = $faker->randomNumber();
                $command->street = 'вул. '.$faker->streetName;
                $command->description = $faker->realText(mt_rand(300, 1000));

                $submission = $session->getStages()->findFirst(fn (string $value, Stage $stage) => $stage->isSubmission());

                $project = $this->projectFactory->create($command, $faker->randomElement($users), $session, $submission->getStartDate());

                $manager->persist($project);
            }
            $manager->flush();
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
