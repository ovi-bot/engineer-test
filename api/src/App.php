<?php

namespace App;

use App\Http\Exceptions\HttpException;
use App\Http\Request;
use App\Http\Response;
use App\Routing\Dispatcher;
use App\Routing\Router;

class App
{
    protected Router $router;
    protected Dispatcher $dispatcher;

    public function __construct(Router $router, Dispatcher $dispatcher)
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws HttpException
     */
    public function run(Request $request): void
    {
        list($controllerClass, $method, $params) = $this->router->resolve($request);

        $controller = $this->dispatcher->instantiate($controllerClass);
        $result = $this->dispatcher->dispatch($controller, $method, $params);

        if ($result !== null) {
            Response::json(['data' => $result]);
        }

        exit;
    }
}