<?php

namespace App\core;

use App\core\Router;
use App\core\Request;

class App
{
    public $route;
    public $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->route = new Router($this->request);
    }

    public function run()
    {
        $this->route->resolve();
    }
}
