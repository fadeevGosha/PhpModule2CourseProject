<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Article\Article;
use App\Entities\Article\ArticleInterface;
use App\Repositories\ArticleRepositoryInterface;
use PDO;

class CreateArticleCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private Connection $connection,
        private ArticleRepositoryInterface $articleRepository
    ){}

    /**
     * @var EntityCommand $command
     */
    public function handle(CommandInterface $command): ArticleInterface
    {
        /**
         * @var Article $article
         */
        $article = $command->getEntity();

        try {

            $this->connection->beginTransaction();
            $stmt = $this->connection->prepare($this->getSQL());

            $stmt->execute(
                [
                    ':author_id' => $article->getAuthor()->getId(),
                    ':title' => $article->getTitle(),
                    ':text' => $article->getText(),
                ]
            );

            $id = $this->connection->lastInsertId();
            $this->connection->commit();
        }
        catch(\PDOException $e ) {
            $this->connection->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }

        return $this->articleRepository->findById($id);
    }

    public function getSQL(): string
    {
        return "INSERT INTO Article (author_id, title, text) 
        VALUES (:author_id, :title, :text)";
    }
}
