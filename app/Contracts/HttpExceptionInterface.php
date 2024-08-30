<?php

namespace App\Contracts;

interface HttpExceptionInterface
{
    public function __construct($message, $code);
    
    public function getMessage(): string;

    public function getCode();
}
