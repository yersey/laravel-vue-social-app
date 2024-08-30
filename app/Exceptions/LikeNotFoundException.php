<?php

namespace App\Exceptions;

use Exception;
use App\Contracts\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class LikeNotFoundException extends Exception implements HttpExceptionInterface
{
    public function __construct($message = 'Like not found.', $code = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($message, $code);
    }
}
