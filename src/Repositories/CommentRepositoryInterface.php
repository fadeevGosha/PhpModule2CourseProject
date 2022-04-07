<?php

namespace App\Repositories;

use App\Entities\Comment\Comment;
use PDOStatement;

interface CommentRepositoryInterface extends EntityRepositoryInterface
{
    public function getComment(PDOStatement $statement, int $id): Comment;
}
