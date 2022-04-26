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
        $this->getParams($path,'GET');
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
        $this->getParams($path,'POST');
    }

    public function delete($path, $callback)
    {
        $this->routes['delete'][$path] = $callback;
        $this->getParams($path,'DELETE');
    }


    public function getParams($path,$method)
    {
        $url = $_SERVER['REQUEST_URI'] ?? '/';

        if (count(explode('/', $url)) == count(explode('/', $path))) {
            $this->request->param = $path;
            $this->request->method = $method;
        }
    }

    public function resolve()
    {
        $this->request->route = $this->routes;

        $path =  $this->request->getPath();
        $method = $this->request->getMethod();

        $this->param = $this->request->getParam($_SERVER['REQUEST_METHOD']);
        $callback = $this->routes[$method][$path] ?? false;

        $controller = new $callback[0]();
        call_user_func_array([$controller, $callback[1]], [$this->param]);
    }
}
