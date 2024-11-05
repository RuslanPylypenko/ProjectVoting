<?php

namespace App\Application\City\Command\Create;

use App\Domain\City\DTO\CityDTO;
use App\Domain\City\Entity\CityEntity;
use App\Domain\Shared\Address\Address;
use App\Domain\User\Entity\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CreateCityHandler extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/cities/create', methods: ['POST'])]
    public function handle(CreateCityCommand $command, #[CurrentUser] UserEntity $userEntity): JsonResponse
    {
        if (null !== $this->entityManager->getRepository(CityEntity::class)->findOneBy(['slug' => $command->slug])) {
            throw new \DomainException('City already exists.');
        }

        $city = new CityEntity(
            $command->title,
            $command->slug,
            new Address($command->title, 'Україна')
        );

        $this->entityManager->persist($city);
        $this->entityManager->flush();

        return new JsonResponse([
            'data' => CityDTO::fromEntity($city),
        ]);
    }
}