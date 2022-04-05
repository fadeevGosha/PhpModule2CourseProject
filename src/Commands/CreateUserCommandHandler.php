<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Connection $connection,
        private LoggerInterface $logger
    ){}

    /**
     * @throws UserEmailExistException
     * @var EntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $this->logger->info('Create user command started');

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
                    ':password' => hash('sha256', '1234567')
               ]
            );

            $this->logger->info("User created email: $email");
        }
        else
        {
            $this->logger->warning("User already exists: $email");
            throw new UserEmailExistException();
        }
    }

    private function isUserExists(string $email): bool
    {
        try {
            $this->userRepository->getUserByEmail($email);
        } catch (UserNotFoundException) {
            $this->logger->warning("User already exists: $email");
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
