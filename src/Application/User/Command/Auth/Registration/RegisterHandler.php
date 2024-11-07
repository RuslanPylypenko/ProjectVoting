<?php

namespace App\Application\User\Command\Auth\Registration;

use App\Domain\Shared\Address\AddressLocator\AddressLocatorInterface;
use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterHandler
{
    public function __construct(
        private UsersRepository $usersRepository,
        private AddressLocatorInterface $addressLocator,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/auth/registration', methods: ['POST'])]
    public function handle(RegisterCommand $command): Response
    {
        if (null !== $this->usersRepository->findByEmail($command->email)) {
            throw new \InvalidArgumentException('User already exists.');
        }

        $user = new UserEntity(
            $command->name,
            $command->email,
            $command->password,
            $this->addressLocator->findAddress(['street' => $command->livingAddress]),
            $this->addressLocator->findAddress(['street' => $command->registrationAddress]),
            new \DateTime($command->birthDate),
        );

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(['user' => UserDTO::fromEntity($user)], Response::HTTP_OK);
    }
}
