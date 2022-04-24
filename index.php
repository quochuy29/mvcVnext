<?php

namespace App;

require_once("./vendor/autoload.php");

use App\core\App;
use MVC\controller\UserController;

$app = new App();

$app->route->get('/users', [UserController::class, 'index']);

$app->route->get('/users/getUser', [UserController::class, 'getUser']);

$app->route->get('/users/{id}', [UserController::class, 'get']);

$app->route->post('/users/create', [UserController::class, 'create']);

$app->run();
