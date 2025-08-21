<?php

namespace App\Controllers;

use App\Http\Exceptions\MethodNotAllowedException;

class Controller
{
    /**
     * @throws MethodNotAllowedException
     */
    protected function verifyRequestMethod(string $requiredMethod): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== $requiredMethod) {
            throw new MethodNotAllowedException();
        }
    }
}