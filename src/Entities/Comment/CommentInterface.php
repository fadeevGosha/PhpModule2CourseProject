<?php

namespace App\Entities\Article;

use App\Entities\EntityInterface;
use App\Entities\User\User;

interface CommentInterface extends EntityInterface
{
    public function getAuthor(): User;
    public function getArticle(): Article;
    public function getText(): string;
}