<?php

namespace App\Application\User\Command\Auth\Me;

use App\Domain\User\DTO\UserDTO;
use App\Domain\User\Entity\UserEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GetAuthUserHandler
{
    #[Route('/users/me', methods: ['GET'])]
    public function hanlde(#[CurrentUser] ?UserEntity $userEntity): Response
    {
        if (null === $userEntity) {
            return $this->json([
                'error' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => UserDTO::fromEntity($userEntity),
        ]);
    }
}
