<?php

namespace App\DataFixtures;

use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Session\Entity\SessionEntity;
use App\Domain\Session\Entity\Stage;
use App\Domain\User\Entity\UserEntity;
use App\Domain\Vote\Entity\VoteEntity;
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
        ini_set('memory_limit', '512M');

        $faker = Factory::create('uk_UA');
        $sessions = $manager->getRepository(SessionEntity::class)
            ->createQueryBuilder('s')
            ->getQuery()
            ->toIterable();

        $totalUsers = $manager->createQueryBuilder()
            ->select('count(u.id)')
            ->from(UserEntity::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();

        foreach ($sessions as $session) {
            $users = $this->randomUsers->get($manager, $session, random_int(10, $totalUsers));

            /** @var Stage $voting */
            $voting = $session->getStages()->findFirst(fn (string $value, Stage $stage) => $stage->isVoting());

            $projects = $manager->getRepository(ProjectEntity::class)
                ->createQueryBuilder('p')
                ->where('p.session = :sessionId')
                ->setParameter('sessionId', $session->getId())
                ->getQuery()
                ->toIterable();


            foreach ($projects as $project) {
                $votes = [];
                foreach ($faker->randomElements($users, random_int(0, count($users))) as $user) {
                    $vote = $this->voteFactory->createVote($user, $project, $session, $voting->getStartDate());
                    $manager->persist($vote);

                    $votes[] = $vote;
                }

                $manager->flush();
                foreach ($votes as $vote) {
                    $manager->detach($vote);
                }

                $manager->detach($project);
            }
            $manager->detach($session);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
