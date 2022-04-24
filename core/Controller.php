<?php

namespace App\core;

class Controller
{
    public function render($view, $params = [])
    {
        extract($params);
        include_once "src/view/$view.php";
    }
}
