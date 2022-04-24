<?php

namespace App\core;

class Router
{

    protected $routes = [];
    public $request;
    public $param = null;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
        $this->getParams($path);
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
        $this->getParams($path);
    }

    public function getParams($path)
    {
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        if (count(explode('/', $url)) == count(explode('/', $path))) {
            $this->request->param = $path;
        }
    }

    public function resolve()
    {
        $path =  $this->request->getPath();

        $method = $this->request->getMethod();

        $this->param = $this->request->getParam();

        $callback = $this->routes[$method][$path] ?? false;

        $controller = new $callback[0]();
        call_user_func_array([$controller, $callback[1]], [$this->param]);
    }
}
