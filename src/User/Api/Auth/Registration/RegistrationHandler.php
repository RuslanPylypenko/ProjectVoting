<?php

namespace App\User\Api\Auth\Registration;

use App\User\Entity\UserEntity;
use App\User\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationHandler extends AbstractController
{
    public function __construct(
        private UsersRepository $usersRepository,
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/auth/registration', methods: ['POST'])]
    public function handle(RegistrationCommand $command): Response
    {
        if (null !== $this->usersRepository->findByEmail($command->email)) {
            throw new \InvalidArgumentException('User already exists.');
        }

        $user = new UserEntity($command->name, $command->email, $command->password);

        $this->em->persist($user);
        $this->em->flush();


        return new JsonResponse(['message' => 'User created'], Response::HTTP_OK);
    }
}