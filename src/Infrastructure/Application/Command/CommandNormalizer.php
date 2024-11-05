<?php

namespace App\Infrastructure\Application\Command;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommandNormalizer
{
    private Serializer $serializer;

    public function __construct()
    {
        $classMetadataFactory       = new ClassMetadataFactory(new AttributeLoader());
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $this->serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, new ReflectionExtractor())],
            ['json' => new JsonEncoder()]
        );
    }

    public function deserialize(string $className, string $jsonData): CommandInterface
    {
        return $this->serializer->deserialize($jsonData, $className, 'json');
    }
}