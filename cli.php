<?php

use App\Factories\ArticleFactory;
use App\Factories\CommentFactory;
use App\Factories\UserFactory;
use App\Types\CliArgumentTypes;

try {
    echo match ($argv[1])
    {
        CliArgumentTypes::USER => UserFactory::getInstance()->create(),
        CliArgumentTypes::ARTICLE => ArticleFactory::getInstance()->create(),
        CliArgumentTypes::COMMENT => CommentFactory::getInstance()->create(),
    };
}catch (UnhandledMatchError $e)
{
    var_dump($e->getMessage());
}
