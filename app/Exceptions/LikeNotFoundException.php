<?php

namespace App\Exceptions;

use Exception;

class LikeNotFoundException extends Exception
{
    public function __construct($message = 'Like not found.')
    {
        parent::__construct($message);
    }
}
