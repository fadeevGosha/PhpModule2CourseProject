<?php

namespace App\Factories;

use App\Entities\Article\Article;

final class ArticleFactory extends Factory implements ArticleFactoryInterface
{
    private UserFactoryInterface $userFactory;

    public function __construct(
        UserFactoryInterface $userFactory
    )
    {
        $this->userFactory = $userFactory;
        parent::__construct();
    }

    public function create() : Article
    {
        return new Article(
            $this->facker->randomDigitNot(0),
            $this->userFactory->create(),
            $this->facker->title(),
            $this->facker->text(),
        );
    }
}