<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class FriendNotFoundException extends Exception
{
    public function __construct($message = 'Friend not found.', $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }
}
