<?php

namespace App\Factories;

use App\Article\Article;

final class ArticleFactory extends Factory
{
    public function create():Article
    {
        return new Article(
            self::$facker->randomDigitNot(0),
            UserFactory::getInstance()->create(),
            self::$facker->title(),
            self::$facker->text(),
        );
    }
}