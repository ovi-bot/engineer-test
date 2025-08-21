<?php

namespace App\Http;

class Request
{
    protected string $method;
    protected string $uriPath;
    protected array $segments;

    public static function generate(): Request
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'api/')) {
            $path = substr($path, 4);
        }

        $path = trim($path, '/');
        return new self($method, $path);
    }

    public function __construct($method, $uriPath)
    {
        $this->method = $method;
        $this->uriPath = $uriPath;
        $this->segments = $uriPath === '' ? [] : explode('/', $uriPath);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->uriPath;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }
}