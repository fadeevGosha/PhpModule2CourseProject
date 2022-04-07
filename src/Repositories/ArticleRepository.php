<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\Article\Article;
use App\Exceptions\ArticleNotFoundException;
use PDO;
use PDOStatement;

class ArticleRepository extends EntityRepository implements ArticleRepositoryInterface
{
    public function __construct(
        Connection $connection,
        private UserRepositoryInterface $userRepository
    )
    {
        $this->connection = $connection;
        parent::__construct($connection);
    }

    /**
     * @throws \Exception
     */
    public function findById(int $id): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM Article WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getArticle($statement, $id);
    }

    private function getArticle(PDOStatement $statement, int $id): Article
    {
        $result = $statement->fetch(PDO::FETCH_OBJ);
        if ($result === false) {
            throw new ArticleNotFoundException(
                sprintf("Cannot find article with id: %s", $id)
            );
        }

        $article =  new Article(
            author : $this->userRepository->findById($result->author_id),
            title : $result->title,
            text : $result->text
        );

        $article->setId($result->id);
        return $article;
    }
}
