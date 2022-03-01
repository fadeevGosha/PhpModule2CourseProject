<?php

use App\Exceptions\MatchException;
use App\Factories\EntityFactory;

try {
    echo EntityFactory::getInstance()->create($argv[1]);
}catch (MatchException $e)
{
    var_dump($e->getMessage());
}
