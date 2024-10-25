<?php

namespace App\Domain\User\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    public function __construct(string $message = 'User not found')
    {
        parent::__construct($message);
    }
}