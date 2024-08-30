<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CommentAlreadyLikedException extends Exception
{
    public function __construct($message = 'You have already liked this comment.', $code = Response::HTTP_CONFLICT)
    {
        parent::__construct($message, $code);
    }
}
