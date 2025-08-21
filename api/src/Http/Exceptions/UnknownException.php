<?php

namespace App\Http\Exceptions;

class UnknownException extends HttpException
{
    public function __construct()
    {
        parent::__construct('', 500);
    }
}