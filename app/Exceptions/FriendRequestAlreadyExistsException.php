<?php

namespace App\Exceptions;

use Exception;

class FriendRequestAlreadyExistsException extends Exception
{
    public function __construct($message = 'Friend request already exists.')
    {
        parent::__construct($message);
    }
}
