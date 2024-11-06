<?php

namespace App\Infrastructure\Application\Command;

use App\Domain\City\Entity\CityEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CityResolver implements ValueResolverInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || CityEntity::class !== $argumentType) {
            return [];
        }

        $city = $this->em->getRepository(CityEntity::class)->findOneBy(['slug' => 'Kyiv']);

        if (null === $city) {
            throw new \RuntimeException('City not found');
        }

        return [$city];
    }
}
