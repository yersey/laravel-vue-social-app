<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class FriendRequestNotFoundException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = 'Friend request not found.', $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }
}
