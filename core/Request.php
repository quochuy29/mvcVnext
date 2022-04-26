<?php

namespace App\core;

class Request
{
    public $param;
    public $method;
    public $route;

    public function __construct()
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $_POST[$key] = $value;
            }
        }
    }

    public function changeFile($file)
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $_POST[$key] = $file;
            }
        }
    }

    public function all()
    {
        if ($this->isMethod() == "GET") {
            return $_GET;
        }
        return $_POST;
    }

    public function isMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function checkCountArrayPath($param,$key)
    {
        $this->param = &$param;
        $this->method = &$key;
 
        if ($this->checkParam($param)) {

            $key = explode("/", $param);
            $params = explode("/", filter_var(rtrim($_SERVER['REQUEST_URI'] ?? '/', '/'), FILTER_SANITIZE_URL));
            if (count($key) == count($params)) {
                return true;
            }
        }

        return false;
    }

    public function checkParam()
    {
        $param = explode('/', $this->param);
        if (strtolower($this->method) == strtolower($this->isMethod())) {
            if (preg_match("/^[\{]([a-z]|[A-Z]){1,20}[\}]$/", end($param))) {
                return true;
            }
        }
        return false;
    }

    public function handleRoute($path, $route)
    {
        if (count($path) == 3) {
            if ($path[1] == $route[1]) {
                return true;
            }
        } elseif (count($path) == 4) {
            if (($path[1] == $route[1]) && ($path[2] == $route[2])) {
                return true;
            }
        } elseif (count($path) == 2) {
            if ($path[1] == $route[1]) {
                return true;
            }
        }
        return false;
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $this->isMethod();
        $position = strpos($path, '?');

        $route = $this->route;

        foreach ($route as $key => $valueRoute) {
            if (strtolower($method) == $key) {
                foreach ($valueRoute as $keyRoute => $valueKeyRoute) {
                    $pathArray = explode('/', $path);
                    $keyRouteArray = explode('/', $keyRoute);
                    if (count($pathArray) == count($keyRouteArray)) {
                        $checkPathRoute = $this->handleRoute($pathArray, $keyRouteArray);
                        if ($checkPathRoute) {
                            if ($this->checkCountArrayPath($keyRoute,$key)) {
                                return $keyRoute;
                            }
                        }
                    }
                }
            }
        }

        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function getParam($method)
    {
        $param = null;
        $this->method = &$method;
        if ($this->checkParam()) {
            $getParam = [];
            $key = explode("/", $this->param);
            $params = explode("/", filter_var(rtrim($_SERVER['REQUEST_URI'] ?? '/', '/'), FILTER_SANITIZE_URL));

            foreach ($key as $keys => $value) {
                if ($params[$keys] !== $value) {
                    $charSpecial_1 = str_replace("{", "", $value);
                    $charSpecial_2 = str_replace("}", "", $charSpecial_1);
                    $getParam[$charSpecial_2] = $params[$keys];
                }
            }

            foreach ($getParam as $value) {
                $param = $value;
            }
            return $param;
        }
        return $param;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
