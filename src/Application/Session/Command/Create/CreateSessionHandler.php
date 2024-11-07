<?php

namespace App\Application\Session\Command\Create;

use App\Domain\City\Entity\CityEntity;
use App\Domain\Session\DTO\SessionDTO;
use App\Domain\Session\Factory\SessionFactory;
use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CreateSessionHandler extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SessionFactory $sessionFactory,
        private SessionRepository $sessionRepository,
    ) {
    }

    #[Route('/sessions/create', methods: ['POST'])]
    public function handle(CreateSessionCommand $command, #[CurrentUser] UserEntity $userEntity, CityEntity $city): JsonResponse
    {
        $session = $this->sessionFactory->create($command, $city);

        if (null !== $this->sessionRepository->findSessionByPeriod($session->getStartDate(), $session->getEndDate())) {
            throw new \DomainException(sprintf('Session with the period from %s to %s already exists.', $session->getStartDate()->format('Y-m-d'), $session->getEndDate()->format('Y-m-d')));
        }

        $this->entityManager->persist($session);
        $this->entityManager->flush();

        return new JsonResponse([
            'session' => SessionDTO::fromEntity($session),
        ]);
    }
}
