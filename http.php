<?php

use App\Http\Actions\CreateArticle;
use App\Http\Actions\CreateUser;
use App\Http\Actions\FindByEmail;
use App\Http\ErrorResponse;
use App\Http\Request;
use Psr\Log\LoggerInterface;
use \App\Exceptions\HttpException;

$container = require __DIR__ . '/bootstrap.php';
/**
 * @var LoggerInterface $logger
 */
$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/user/show' => FindByEmail::class
    ],
    'POST' => [
        '/user/create' => CreateUser::class,
        '/article/create' => CreateArticle::class
    ],
];

if (!array_key_exists($method, $routes)) {
    $logger->info(sprintf('Клиент с IP-адресом :%s пытался получить несуществующий роут', $_SERVER['REMOTE_ADDR']));

    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];


try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
























