<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Article\Article;

class CreateArticleCommandHandler implements CommandHandlerInterface
{
    public function __construct(private Connection $connection){}

    /**
     * @var EntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var Article $article
         */
        $article = $command->getEntity();

        $this->connection->prepare($this->getSQL())->execute(
            [
                ':author_id' => $article->getAuthorId(),
                ':title' => $article->getTitle(),
                ':text' => $article->getText(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO Article (author_id, title, text) 
        VALUES (:author_id, :title, :text)";
    }
}
