<?php

namespace App\User\Api\Auth\Me;

use App\User\DTO\UserDTO;
use App\User\Entity\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetAuthUserHandler extends AbstractController
{
    #[Route('/users/me', methods: ['GET'])]
    public function hanlde(): Response
    {
        /** @var UserEntity $user */
        $user = $this->getUser();
        if (null === $user) {
            return $this->json([
                'error' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => UserDTO::fromEntity($user)
        ]);
    }
}