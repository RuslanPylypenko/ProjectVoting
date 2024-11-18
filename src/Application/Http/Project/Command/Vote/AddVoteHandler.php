<?php

namespace App\Application\Http\Project\Command\Vote;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Project\Entity\ProjectEntity;
use App\Domain\Vote\Factory\VoteFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AddVoteHandler
{
    public function __construct(
        private VoteFactory $voteFactory,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/projects/{project}/vote', methods: ['POST'])]
    public function handle(ProjectEntity $project, #[CurrentUser] $user, CityEntity $city): JsonResponse
    {
        $vote = $this->voteFactory->createVote($user, $project, $city->getCurrentSession());

        $this->em->persist($vote);
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }
}
