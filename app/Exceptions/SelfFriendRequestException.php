<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class SelfFriendRequestException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = 'You cannot send a friend request to yourself.', $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
