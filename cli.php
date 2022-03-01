<?php

use App\Factories\EntityFactory;

try {
    echo EntityFactory::getInstance()->create($argv[1]);
}catch (UnhandledMatchError $e)
{
    var_dump($e->getMessage());
}
