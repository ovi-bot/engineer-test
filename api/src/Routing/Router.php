<?php

namespace App\Routing;

use App\Http\Exceptions\NotFoundException;
use App\Http\Request;

class Router
{
    /**
     * @throws NotFoundException
     */
    public function resolve(Request $request): array
    {
        $urlSegments = $request->getSegments();

        $controllerSegment = isset($urlSegments[0]) && $urlSegments[0] !== '' ? $urlSegments[0] : null;

        if ($controllerSegment === null) {
            throw new NotFoundException();
        }

        $methodSegment = isset($urlSegments[1]) && $urlSegments[1] !== '' ? $urlSegments[1] : 'index';

        $params = array_slice($urlSegments, 2);

        $controllerClass = ucfirst($controllerSegment) . 'Controller';

        return [$controllerClass, $methodSegment, $params];
    }
}