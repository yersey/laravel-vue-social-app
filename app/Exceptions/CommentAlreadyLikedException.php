<?php

namespace App\Exceptions;

use Exception;

class CommentAlreadyLikedException extends Exception
{
    public function __construct($message = 'You have already liked this comment.')
    {
        parent::__construct($message);
    }
}
