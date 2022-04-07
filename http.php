<?php

use App\Http\Actions\CreateArticle;
use App\Http\Actions\CreateUser;
use App\Http\Actions\FindByEmail;
use App\Http\Actions\LogIn;
use App\Http\ErrorResponse;
use App\Http\Request;
use \App\Exceptions\HttpException;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (\App\Exceptions\HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/user/show' => FindByEmail::class
    ],
    'POST' => [
        '/user/create' => CreateUser::class,
        '/article/create' => CreateArticle::class,
        '/login' => LogIn::class,
    ],
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Method not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Path not found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
