<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class SelfFriendRequestException extends Exception
{
    public function __construct($message = 'You cannot send a friend request to yourself.', $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
