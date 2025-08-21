<?php

namespace App\Http\Exceptions;

use Exception;

class HttpException extends Exception
{
    protected int $status;

    protected array $statusTextMap = [
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];

    public function __construct($message, $status = 500, $code = 0, Exception $previous = null)
    {
        $this->status = (int)$status;
        parent::__construct($message, $code, $previous);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getStatusText(): string
    {
        return $this->statusTextMap[$this->status] ?? ('HTTP ' . $this->status);
    }
}