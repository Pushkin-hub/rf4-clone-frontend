<?php

use Slim\App;
use Controllers\AuthController;
use Controllers\PostController;
use Controllers\UserController;

$app = new App();

$app->post('/login', [AuthController::class, 'login']);
$app->post('/register', [AuthController::class, 'register']);

$app->get('/posts', [PostController::class, 'index']);
$app->post('/posts', [PostController::class, 'store']);
$app->get('/posts/{id}', [PostController::class, 'show']);
$app->put('/posts/{id}', [PostController::class, 'update']);
$app->delete('/posts/{id}', [PostController::class, 'destroy']);

$app->get('/users', [UserController::class, 'index']);
