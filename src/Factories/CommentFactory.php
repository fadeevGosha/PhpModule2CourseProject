<?php

namespace App\Factories;

use App\Comment\Comment;

final class CommentFactory extends Factory
{
    public function create(): Comment
    {
        return new Comment(
            self::$facker->randomDigitNot(0),
            UserFactory::getInstance()->create(),
            ArticleFactory::getInstance()->create(),
            self::$facker->text()
        );
    }
}