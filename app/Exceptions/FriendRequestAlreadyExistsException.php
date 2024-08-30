<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class FriendRequestAlreadyExistsException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = 'Friend request already exists.', $code = Response::HTTP_CONFLICT)
    {
        parent::__construct($message, $code);
    }
}
