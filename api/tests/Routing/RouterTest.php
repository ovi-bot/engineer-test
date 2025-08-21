<?php

namespace Tests\Routing;

use App\Http\Request;
use App\Routing\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testResolveBuildsControllerMethodAndParams(): void
    {
        $req = new Request('GET', 'employee/list/42/extra');

        $router = new Router();
        [$controller, $method, $params] = $router->resolve($req);

        $this->assertSame('EmployeeController', $controller);
        $this->assertSame('list', $method);
        $this->assertSame(['42', 'extra'], $params);
    }

    public function testResolveDefaultsToIndexMethod(): void
    {
        $req = new Request('GET', 'company');

        $router = new Router();
        [$controller, $method, $params] = $router->resolve($req);

        $this->assertSame('CompanyController', $controller);
        $this->assertSame('index', $method);
        $this->assertSame([], $params);
    }
}