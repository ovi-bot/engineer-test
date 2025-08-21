<?php

namespace App\Http\Exceptions;

class MethodNotAllowedException extends HttpException
{
    public function __construct()
    {
        parent::__construct('', 405);
    }
}