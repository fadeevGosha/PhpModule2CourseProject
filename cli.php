<?php

use App\Enums\Argument;
use App\Factories\EntityFactory;
use App\Factories\RepositoryFactory;

$user = EntityFactory::getInstance()->create(Argument::USER->value);
echo $user;
$factory = new RepositoryFactory();
$entityRepository = $factory->create($user);

$entityRepository->save($user);
$entityRepository->get($user->getId());


$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

//Вставляем строку в таблицу пользователей
$connection->exec(
    "INSERT INTO users (first_name, last_name) VALUES ('Ivan', 'Nikitin')"
);

