<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
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
    public function handle(CommandInterface $command): void
    {
        /**
         * @var User $user
         */
        $user = $command->getEntity();
        $email = $user->getEmail();

        if(!$this->isUserExists($email))
        {
            $this->connection->prepare($this->getSQL())->execute(
                [
                    ':firstName' => $user->getFirstName(),
                    ':lastName' => $user->getLastName(),
                    ':email' => $email,
                    ':password' => $user->setPassword($user->getPassword())
                ]
            );
        }
        else
        {
            throw new UserEmailExistException();
        }
    }

    private function isUserExists(string $email): bool
    {
        try {
            $this->userRepository->getUserByEmail($email);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }

    public function getSQL(): string
    {
        return "INSERT INTO User (first_name, last_name, email, password) 
        VALUES (:firstName, :lastName, :email, :password)";
    }
}
