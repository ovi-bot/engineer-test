<?php

namespace App\Http\Exceptions;

class BadRequestException extends HttpException
{
    public function __construct(string $message = '')
    {
        parent::__construct($message, 400);
    }
}