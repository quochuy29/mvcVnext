<?php

namespace App\core;

class Request
{
    public $param;

    public function __construct()
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $_POST[$key] = $value;
            }
        }
    }

    public function changeFile($file){
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

    public function checkCountArrayPath()
    {
        if ($this->checkParam()) {
            $key = explode("/", $this->param);
            $params = explode("/", filter_var(rtrim($_SERVER['REQUEST_URI'] ?? '/', '/'), FILTER_SANITIZE_URL));

            if (count($key) == count($params)) {
                return true;
            }
        }

        return false;
    }

    public function checkParam()
    {
        if (preg_match("/{/", $this->param)) {
            return true;
        }

        return false;
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($this->checkCountArrayPath()) {
            return $this->param;
        }
        
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function getParam()
    {
        $param = null;
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
