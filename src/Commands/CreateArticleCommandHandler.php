<?php

namespace App\Commands;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;

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
        $article = $command->getEntity();
        $this->stmt->execute(
            [
                ':author_id' => $article->getAuthorId(),
                ':header' => $article->getHeader(),
                ':text' => $article->getText(),
            ]
        );
    }

    public function getSQL(): string
    {
        return "INSERT INTO articles (author_id, header, text) 
        VALUES (:author_id, :header, :text)";
    }
}
