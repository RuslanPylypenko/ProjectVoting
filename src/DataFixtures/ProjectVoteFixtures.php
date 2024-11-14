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
    public function __construct(private VoteFactory $voteFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $sessions = $manager->getRepository(SessionEntity::class)->findAll();

        $totalUsers = $manager->createQueryBuilder()
            ->select('count(u.id)')
            ->from(UserEntity::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();

        foreach ($sessions as $session) {
            $users = $this->getRandomUsers($manager, $totalUsers, $session);

            /** @var Stage $voting */
            $voting = $session->getStages()->findFirst(fn (string $value, Stage $stage) => $stage->isVoting());

            $projects = $manager->getRepository(ProjectEntity::class)
                ->createQueryBuilder('p')
                ->where('p.session = :sessionId')
                ->setParameter('sessionId', $session->getId())
                ->getQuery();

            foreach ($projects->toIterable() as $project) {
                foreach ($users as $user) {
                    $vote = $this->voteFactory->createVote($user, $project, $session, $voting->getStartDate());
                    $manager->persist($vote);
                }

                $manager->flush();
            }
        }
    }

    /**
     * @return UserEntity[]
     */
    private function getRandomUsers(ObjectManager $manager, int $totalUsers, SessionEntity $session): array
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
            ProjectFixtures::class,
        ];
    }
}
