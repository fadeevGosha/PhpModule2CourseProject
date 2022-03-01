<?php

namespace App\Factories;

use App\Entities\Comment\Comment;

final class CommentFactory extends Factory implements CommentFactoryInterface
{
    private UserFactoryInterface $userFactory;
    private ArticleFactoryInterface $articleFactory;

    public function __construct(
        UserFactoryInterface $userFactory,
        ArticleFactoryInterface $articleFactory
    )
    {
        $this->userFactory = $userFactory;
        $this->articleFactory = $articleFactory;
        parent::__construct();
    }

    public function create(): Comment
    {
        return new Comment(
            $this->facker->randomDigitNot(0),
            $this->userFactory->create(),
            $this->articleFactory->create(),
            $this->facker->text()
        );
    }
}