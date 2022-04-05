<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use PDO;
use PDOStatement;
use Psr\Log\LoggerInterface;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    public function __construct(
        Connection $connection,
        private LoggerInterface $logger
    )
    {
        parent::__construct($connection);
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(int $id): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM User WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return  $this->getUser($statement);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUserByEmail(string $email): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM User WHERE email = :email'
        );

        $statement->execute([
            ':email' => $email,
        ]);

        return $this->getUser($statement);
    }

    private function getUser(PDOStatement $statement): User
    {
        $userData = $statement->fetch(PDO::FETCH_OBJ);

        if (!$userData) {
            $this->logger->error('User not found');
            throw new UserNotFoundException('User not found');
        }

        $user =  new User(
            $userData->first_name,
            $userData->last_name,
            $userData->email
        );

        $user->setId($userData->id);

        return $user;
    }
}