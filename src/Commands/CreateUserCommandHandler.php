<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Repositories\UserRepositoryInterface;

class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Connection $connection){}

    /**
     * @throws UserEmailExistException
     * @var EntityCommand $command
     */
    public function handle(CommandInterface $command): User
    {
        /**
         * @var User $user
         */
        $user = $command->getEntity();
        $email = $user->getEmail();

        $this->connection->prepare($this->getSQL())->execute(
            [
                ':firstName' => $user->getFirstName(),
                ':lastName' => $user->getLastName(),
                ':email' => $email,
                ':password' => $user->setPassword($user->getPassword())
            ]
        );

        return $user->getId() ? $user : $this->userRepository->findById($this->connection->lastInsertId());
    }

    public function getSQL(): string
    {
        return "INSERT INTO User (first_name, last_name, email, password) 
        VALUES (:firstName, :lastName, :email, :password)
        ON CONFLICT (email) DO UPDATE SET
               first_name = :firstName,
               last_name = :lastName
        ";
    }
}
