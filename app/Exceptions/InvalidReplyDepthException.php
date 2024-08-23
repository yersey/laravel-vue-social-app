<?php

namespace App\Exceptions;

use Exception;

class InvalidReplyDepthException extends Exception
{
    public function __construct($message = 'Replying to this comment is not allowed.')
    {
        parent::__construct($message);
    }
}
