<?php

use App\Factories\EntityFactory;

try {
    echo new EntityFactory($argv[1]);
}catch (UnhandledMatchError $e)
{
    var_dump($e->getMessage());
}
