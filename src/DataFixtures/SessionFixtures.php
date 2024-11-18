<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Application\Http\Session\Command\Create\CreateSessionCommand;
use App\Application\Http\Session\Command\Create\SubmissionRequirements;
use App\Application\Http\Session\Command\Create\VotingRequirements;
use App\Application\Http\Session\Command\Create\WinnerRequirements;
use App\Domain\City\Entity\CityEntity;
use App\Domain\Session\Enum\StageName;
use App\Domain\Session\Factory\SessionFactory;
use App\Domain\Shared\Enum\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SessionFactory $sessionFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('uk_UA');

        $cities = $manager->getRepository(CityEntity::class)->findAll();
        foreach ($cities as $city) {
            $sessionsCount = random_int(2, 4);
            for ($i = 0; $i <= $sessionsCount; ++$i) {
                $end = null;
                $start = (new \DateTime())->modify('-'.random_int(0, 5).' weeks')->modify('-'.$i.' years');

                $stages = [];
                foreach (StageName::cases() as $key => $stageName) {
                    $start = $end
                        ? (clone $end)->modify('+1 day')->setTime(0, 0)
                        : (clone $start)->modify("+$key week")->setTime(0, 0);
                    $end = (clone $start)->modify("+$key week")->modify('+2 week')->setTime(23, 59);

                    $stages[] = [
                        'name' => $stageName->value,
                        'start_date' => $start->format('Y-m-d'),
                        'end_date' => $end->format('Y-m-d'),
                    ];
                }

                $submission = new SubmissionRequirements(
                    12,
                    $faker->randomElements(Category::VALUES),
                    $min = random_int(10_000, 1_000_000),
                    random_int($min, 1_000_000),
                    $faker->boolean()
                );

                $voting = new VotingRequirements(
                    random_int(50, 10_000),
                    12,
                    $faker->boolean(),
                );

                $winner = new WinnerRequirements(
                    random_int(30, 50),
                    random_int(40, 10_000),
                );

                $command = new CreateSessionCommand(
                    'Бюджет міста '.date('Y', $start->getTimestamp()),
                    $stages,
                    $submission,
                    $voting,
                    $winner,
                );

                $session = $this->sessionFactory->create($command, $city);

                $manager->persist($session);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
