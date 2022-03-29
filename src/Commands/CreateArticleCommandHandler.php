<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Entities\Article\Article;

class CreateArticleCommandHandler implements CommandHandlerInterface
{
    private \PDOStatement|false $stmt;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqlLiteConnector();
        $this->stmt = $this->connector->getConnection()->prepare($this->getSQL());
    }

    /**
     * @var CreateEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var Article $article
         */
        $article = $command->getEntity();
        $this->stmt->execute(
            [
                ':author_id' => $article->getAuthorId(),
                ':title' => $article->getTitle(),
                ':text' => $article->getText(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO articles (author_id, title, text) 
        VALUES (:author_id, :title, :text)";
    }
}
