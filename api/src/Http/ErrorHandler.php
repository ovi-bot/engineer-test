<?php

namespace App\Http;

use App\Http\Exceptions\HttpException;
use Throwable;

class ErrorHandler
{
    public static function registerAll(): void
    {
        set_exception_handler([__CLASS__, 'handleException']);
        register_shutdown_function([__CLASS__, 'handleShutdown']);
    }

    public static function handleException(Throwable $e): void
    {
        if ($e instanceof HttpException) {
            Response::json([
                'error' => $e->getStatusText(),
                'message' => $e->getMessage(),
                'code' => $e->getStatus(),
            ], $e->getStatus());
        }

        Response::json([
            'error' => 'Internal Server Error',
            'message' => $e->getMessage(),
            'code' => 500,
        ], 500);
    }

    public static function handleShutdown(): void
    {
        $err = error_get_last();

        if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');

            echo json_encode([
                'error' => 'Internal Server Error',
                'message' => 'Fatal error: ' . $err['message'],
                'code' => 500
            ]);
        }
    }
}