<?php

namespace App\Exceptions;

use Exception;

class FriendNotFoundException extends Exception
{
    public function __construct($message = 'Friend not found.')
    {
        parent::__construct($message);
    }
}
