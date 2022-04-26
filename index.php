<?php

namespace App;

require_once("./vendor/autoload.php");

use App\core\App;
use MVC\controller\UserController;

$app = new App();

$app->route->get('/users', [UserController::class, 'index']);

$app->route->get('/users/getUser', [UserController::class, 'getUser']);

$app->route->post('/users/create', [UserController::class, 'create']);

$app->route->get('/users/getUser/{id}', [UserController::class, 'getUserId']);

$app->route->post('/users/edit/{id}',[UserController::class,'update']);

$app->route->post('/users/copy/{id}',[UserController::class,'copy']);

$app->route->delete('/users/{id}',[UserController::class,'delete']);

$app->run();
