<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PostAlreadyLikedException extends Exception
{
    public function __construct($message = 'You have already liked this post.', $code = Response::HTTP_CONFLICT)
    {
        parent::__construct($message, $code);
    }
}
