<?php

namespace System\Router;

use System\Http\Request;
use System\Http\Response;
use System\RestException;

final class Router
{

    private $router = [];

    private $matchRouter = [];

    private $url;

    private $method;

    private $params = [];

    private $response;

    private $request;

    private $data;

    public function __construct(Request $request, Response $response)
    {
        $this->url = rtrim($request->getUrl(), '/');
        $this->method = $request->getMethod();
        $this->request = $request;
        $this->response = $response;
    }

    public function get($pattern, $callback)
    {
        $this->addRoute("GET", $pattern, $callback);
    }

    public function post($pattern, $callback)
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    public function delete($pattern, $callback)
    {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    public function put($pattern, $callback)
    {
        $this->addRoute('PUT', $pattern, $callback);
    }

    public function addRoute($method, $pattern, $callback)
    {
        array_push($this->router, new Route($method, $pattern, $callback));
    }

    private function getMatchRoutersByRequestMethod()
    {
        foreach ($this->router as $value) {
            if (strtoupper($this->method) == $value->getMethod()) {
                array_push($this->matchRouter, $value);
            }
        }
    }

    private function getMatchRoutersByPattern($pattern)
    {
        $this->matchRouter = [];
        foreach ($pattern as $value) {
            if ($this->dispatch(str_replace(['%20', ' '], '-', $this->url), $value->getPattern())) {
                array_push($this->matchRouter, $value);
            }
        }
    }

    public function dispatch($url, $pattern)
    {
        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

        if (substr($pattern, -1) === '/') {
            $patternAsRegex = $patternAsRegex . '?';
        }
        $patternAsRegex = '@^' . $patternAsRegex . '$@';

        if (preg_match($patternAsRegex, $url, $paramsValue)) {
            array_shift($paramsValue);
            foreach ($params[0] as $value) {
                $val = substr($value, 1);
                if ($paramsValue[$val]) {
                    $this->setParams($val, urlencode($paramsValue[$val]));
                }
            }

            return true;
        }

        return false;
    }

    public function getRouter()
    {
        return $this->router;
    }

    private function setParams($key, $value)
    {
        $this->params[$key] = $value;
    }

    private function convertPatternToRegex($matches)
    {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    public function run()
    {
        if (!is_array($this->router) || empty($this->router))
            throw new \Exception('No Object Route Set');

        $this->getMatchRoutersByRequestMethod();
        $this->getMatchRoutersByPattern($this->matchRouter);

        if ($this->method == 'PUT' || $this->method == 'POST' || $this->method == 'PATCH') {
            $this->data = $this->request->getData();
        }

        if (!$this->matchRouter || empty($this->matchRouter)) {
            $this->sendNotFound();
        } else {
            if (is_callable($this->matchRouter[0]->getCallback()))
                call_user_func($this->matchRouter[0]->getCallback(), $this->params);
            else
                $this->runController($this->matchRouter[0]->getCallback(), $this->params);
        }
    }

    private function runController($controller, $params)
    {
        $parts = explode('@', $controller);
        $file = CONTROLLERS . ucfirst($parts[0]) . '.php';

        if (file_exists($file)) {
            require_once $file;

            $controller = 'App\\Controllers\\' . ucfirst($parts[0]);

            if (class_exists($controller))
                $controller = new $controller();
            else
                $this->sendNotFound();

            if (isset($parts[1])) {
                $method = $parts[1];

                if (!method_exists($controller, $method))
                    $this->sendNotFound();
            } else {
                $method = 'index';
            }

            try {
                if (!$this->isAuthorized($controller)) {
                    $this->unauthorized($controller);
                }

                $params['data'] = $this->data;
                $params['query'] = $_GET;

                if (is_callable([$controller, $method])) {
                    return call_user_func([$controller, $method], $params);
                } else {
                    $this->sendNotFound();
                }
            } catch (RestException $e) {
                $this->response
                    ->sendStatus($e->getCode())
                    ->setContent([
                        'error' => $e->getMessage(),
                        'status_code' => $e->getCode()
                    ]);
            }
        }
    }

    private function isAuthorized($classObj)
    {
        if (method_exists($classObj, 'isAuthenticated')) {
            return $classObj->isAuthenticated();
        }

        return true;
    }

    protected function unauthorized()
    {
        throw new RestException(401, "You are not authorized to access this resource.");
    }

    private function sendNotFound()
    {
        $this->response
            ->sendStatus(404)
            ->setContent([
                'error' => 'Sorry This Route Not Found !',
                'status_code' => 404
            ]);
    }
}
