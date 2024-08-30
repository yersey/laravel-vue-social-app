<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class FriendshipAlreadyExistsException extends Exception
{
    public function __construct($message = 'Friendship already exists.', $code = Response::HTTP_CONFLICT)
    {
        parent::__construct($message, $code);
    }
}
