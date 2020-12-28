<?php

namespace System\Router;

final class Route
{
    private $method;

    private $pattern;

    private $callback;

    private $list_method = ['GET', 'POST', 'PUT', 'DELETE', 'OPTION'];

    public function __construct(string $method, string $pattern, string $callback)
    {
        $this->method = $this->validateMethod(strtoupper($method));
        $this->pattern = str_replace(['%20', ' '], '-', $pattern);
        $this->callback = $callback;
    }

    private function validateMethod(string $method)
    {
        if (in_array(strtoupper($method), $this->list_method))
            return $method;

        throw new \Exception('Method Not Found');
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getCallback()
    {
        return $this->callback;
    }
}
