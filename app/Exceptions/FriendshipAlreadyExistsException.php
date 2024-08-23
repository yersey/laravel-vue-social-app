<?php

namespace App\Exceptions;

use Exception;

class FriendshipAlreadyExistsException extends Exception
{
    public function __construct($message = 'Friendship already exists.')
    {
        parent::__construct($message);
    }
}
