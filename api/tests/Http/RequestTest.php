<?php

namespace Tests\Http;

use App\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        $_SERVER = [];
    }

    public function testGenerateStripsApiPrefixAndParsesSegments(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/employee/updateEmail/123?foo=bar';

        $req = Request::generate();

        $this->assertSame('GET', $req->getMethod());
        $this->assertSame('employee/updateEmail/123', $req->getPath());
        $this->assertSame(['employee', 'updateEmail', '123'], $req->getSegments());
    }

    public function testGenerateHandlesRootPath(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/';

        $req = Request::generate();

        $this->assertSame('POST', $req->getMethod());
        $this->assertSame('', $req->getPath());
        $this->assertSame([], $req->getSegments());
    }

    public function testGenerateTrimsLeadingAndTrailingSlashes(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['REQUEST_URI'] = '/api//employee//list///';

        $req = Request::generate();

        $this->assertSame('PUT', $req->getMethod());
        $this->assertSame('employee//list', $req->getPath());
        $this->assertSame(['employee', '', 'list'], $req->getSegments());
    }

    public function testConstructorSetsFieldsDirectly(): void
    {
        $req = new Request('DELETE', 'company/42');

        $this->assertSame('DELETE', $req->getMethod());
        $this->assertSame('company/42', $req->getPath());
        $this->assertSame(['company', '42'], $req->getSegments());
    }
}