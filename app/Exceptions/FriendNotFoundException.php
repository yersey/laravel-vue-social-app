<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class FriendNotFoundException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = 'Friend not found.', $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }
}
