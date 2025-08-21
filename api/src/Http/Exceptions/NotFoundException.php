<?php

namespace App\Http\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct('', 404);
    }
}