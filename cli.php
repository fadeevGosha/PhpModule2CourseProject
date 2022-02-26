<?php

use App\Factories\ArticleFactory;
use App\Factories\CommentFactory;
use App\Factories\UserFactory;
use App\Types\CliArgumentTypes;

switch ($argv[1]) {
    case CliArgumentTypes::USER:
        echo UserFactory::getInstance()->create();
        break;
    case CliArgumentTypes::ARTICLE:
        echo ArticleFactory::getInstance()->create();
        break;
    case CliArgumentTypes::COMMENT:
        echo CommentFactory::getInstance()->create();
        break;
    default:
        http_response_code(404);
}
