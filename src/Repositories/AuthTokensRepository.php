<?php

namespace App\Repositories;

use App\Drivers\Connection;
use App\Entities\User\AuthToken;
use App\Exceptions\AuthTokenNotFoundException;
use App\Exceptions\AuthTokensRepositoryException;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use PDOException;

class AuthTokensRepository implements AuthTokensRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function save(AuthToken $authToken): void
    {
        $query = <<<'SQL'
           INSERT INTO tokens (
               token,
               user_uuid,
               expires_on
           ) VALUES (
               :token,
               :user_uuid,
               :expires_on
           )
           ON CONFLICT (token) DO UPDATE SET
               expires_on = :expires_on
SQL;
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':token' => $authToken,
                ':user_uuid' => $authToken->getUserId(),
                ':expires_on' => $authToken->expiresOn()
                    ->format(DateTimeInterface::ATOM),
            ]);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }
    }

    public function get(string $token): AuthToken
    {
        try {
            $statement = $this->connection->prepare(
                'SELECT * FROM tokens WHERE token = ?'
            );
            $statement->execute([$token]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), (int)$e->getCode(), $e
            );
        }

        if (false === $result) {
            throw new AuthTokenNotFoundException("Cannot find token: $token");
        }

        try {
            return new AuthToken(
                $result['token'],
                $result['user_uuid'],
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $e) {
            throw new AuthTokensRepositoryException(
                $e->getMessage(), $e->getCode(), $e
            );
        }
    }

}