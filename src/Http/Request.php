<?php

namespace Araecy\Framework\Http;

class Request
{
    private static $instance = null; //singleton pattern

    private function __construct(
        private array $server,
        private array $get,
        private array $post,
        private array $files,
        private array $cookies,
        private array $env,
    ) {
    }

    public static function create(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(
                $_SERVER,
                $_GET,
                $_POST,
                $_FILES,
                $_COOKIE,
                $_ENV
            );
        }

        return static::$instance;
    }
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUri(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getPostParams(string $name): string
    {
        return $this->post[$name] ?? '';
    }
}