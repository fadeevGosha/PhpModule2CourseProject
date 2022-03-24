<?php

use App\Http\Actions\CreateUser;
use App\Http\Actions\FindByEmail;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\SuccessfulResponse;
use App\Repositories\UserRepository;

require_once __DIR__ . '/vendor/autoload.php';

//$request = new Request($_GET, $_SERVER);
//
//try {
//    $parameter = $request->query('some_parameter');
//    $header = $request->header('Some-Header');
//    $path = $request->path();
//} catch (\App\Exceptions\HttpException $e) {
//    echo $e->getMessage();
//    die(0);
//}
//
//$response = new SuccessfulResponse([
//    'message' => 'Hello from PHP',
//]);
//
//$response->send();



//
//$request = new Request($_GET, $_SERVER);
//
//try {
//    $path = $request->path();
//} catch (\App\Exceptions\HttpException) {
//    (new ErrorResponse)->send();
//    return;
//}
//
//$routes = [
//    '/user/show' => new FindByEmail(new UserRepository()),
//];
//
//if (!array_key_exists($path, $routes)) {
//    (new ErrorResponse('Not found'))->send();
//    return;
//}
//
//$action = $routes[$path];
//
//try {
//    $response = $action->handle($request);
//} catch (Exception $e) {
//    (new ErrorResponse($e->getMessage()))->send();
//}
//
//$response->send();
//


$request = new Request(
    $_GET,
    $_SERVER,
    // Читаем поток, содержащий тело запроса
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException) {
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
        '/user/show' => new FindByEmail()
    ],
    'POST' => [
        '/user/create' => new CreateUser(),
    ],
];

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

// Выбираем действие по методу и пути
$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
