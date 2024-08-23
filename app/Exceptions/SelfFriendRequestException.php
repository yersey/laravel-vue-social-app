<?php

namespace App\Exceptions;

use Exception;

class SelfFriendRequestException extends Exception
{
    public function __construct($message = 'You cannot send a friend request to yourself.')
    {
        parent::__construct($message);
    }
}
