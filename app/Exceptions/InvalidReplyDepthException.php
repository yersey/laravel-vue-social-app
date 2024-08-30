<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class InvalidReplyDepthException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = 'Replying to this comment is not allowed.', $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
