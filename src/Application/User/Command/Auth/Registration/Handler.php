<?php

namespace App\Application\User\Command\Auth\Registration;

use App\Domain\User\Entity\UserEntity;
use App\Infrastructure\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Handler extends AbstractController
{
    public function __construct(
        private UsersRepository     $usersRepository,
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/auth/registration', methods: ['POST'])]
    public function handle(Command $command): Response
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