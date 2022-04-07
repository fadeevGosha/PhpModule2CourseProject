<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Comment\Comment;

class CreateCommentCommandHandler implements CommandHandlerInterface
{
    public function __construct(private Connection $connection){}

    /**
     * @var EntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var Comment $comment
         */
        $comment = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute(
            [
                ':author_id' => $comment->getAuthorId(),
                ':article_id' => $comment->getArticleId(),
                ':comment' => $comment->getText(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO Comment(author_id, article_id, text) 
        VALUES (:author_id, :article_id, :comment)";
    }
}
