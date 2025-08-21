<?php

namespace App\Routing;

use App\Http\Exceptions\NotFoundException;

class Dispatcher
{
    /**
     * @throws NotFoundException
     */
    public function instantiate(string $controllerClassName)
    {
        $file = dirname(__DIR__) . '/Controllers/' . $controllerClassName . '.php';

        if (!is_file($file)) {
            throw new NotFoundException();
        }

        require_once $file;

        $controllerClass = 'App\Controllers\\' . $controllerClassName;

        if (!class_exists($controllerClass, false)) {
            throw new NotFoundException();
        }

        return new $controllerClass();
    }

    /**
     * @throws NotFoundException
     */
    public function dispatch($controller, $method, array $params)
    {
        if (!method_exists($controller, $method)) {
            throw new NotFoundException();
        }

        return call_user_func_array([$controller, $method], $params);
    }
}