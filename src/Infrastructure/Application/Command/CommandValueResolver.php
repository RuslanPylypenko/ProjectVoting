<?php

namespace App\Infrastructure\Application\Command;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandValueResolver implements ValueResolverInterface
{
    public function __construct(
        private CommandNormalizer $commandNormalizer,
        private ValidatorInterface $validator,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_subclass_of($argumentType, CommandInterface::class)) {
            return [];
        }

        $command = $this->commandNormalizer->denormalize($argumentType, $request->toArray());
        $errors  = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidationFailedException($command, $errors);
        }

        return [$command];
    }
}