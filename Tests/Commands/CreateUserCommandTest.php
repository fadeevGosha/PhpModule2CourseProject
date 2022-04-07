<?php

namespace Tests\Commands;

use App\Commands\EntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\Commands\SymfonyCommands\CreateUser;
use App\config\SqlLiteConfig;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Traits\ContainerTrait;
use Symfony\Component\Console\Exception\RuntimeException;

class CreateUserCommandTest extends TestCase
{
    use ContainerTrait;

    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $createUserCommandHandler = $this->makeCommandHandler();

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $createUserCommandHandler->handle($this->makeUserCommand());
    }

    public function testItThrowsAnExceptionWhenUserAlreadyExistsByAnonymous(): void
    {
        $createUserCommandHandler = $this->makeCommandHandler();

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $createUserCommandHandler->handle($this->makeUserCommand());
    }

    public function testItSavesUserToRepository(): void
    {
        $userRepository = $this->makeUsersRepository();
        $createUserCommandHandler = $this->makeCommandHandler();

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $createUserCommandHandler->handle($this->makeUserCommand());
        $this->assertTrue($userRepository->wasCalled());
    }


    public function testItRequiresLastName(): void
    {
        $command = new CreateUser($this->makeUsersRepository(), $this->makeCommandHandler());
        $this->expectException(RuntimeException::class);

        $this->expectExceptionMessage('Not enough arguments (missing: "lastName").');

        $command->run(
            new ArrayInput([
                'email' => 'Ivan',
                'password' => 'some_password',
                'firstName' => 'Ivan',

            ]),
            new NullOutput()
        );
    }

    public function testItRequiresPassword(): void
    {
        $command = new CreateUser($this->makeUsersRepository(), $this->makeCommandHandler());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "firstName, lastName, password"'
        );

        $command->run(
            new ArrayInput([
                'email' => 'Ivan',
            ]),
            new NullOutput()
        );
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUser($this->makeUsersRepository(), $this->makeCommandHandler());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Not enough arguments (missing: "firstName, lastName").'
        );

        $command->run(
            new ArrayInput([
                'email' => 'Ivan',
                'password' => 'some_password',
            ]),
            new NullOutput()
        );
    }

    public function testItSavesUser(): void
    {
        $userRepository = $this->makeUsersRepository();
        $command = new CreateUser($userRepository, $this->makeCommandHandler());

        $command->run(
            new ArrayInput([
                'email' => 'Ivan',
                'password' => 'some_password',
                'firstName' => 'Ivan',
                'lastName' => 'Nikitin',
            ]),
            new NullOutput()
        );

        $this->assertTrue($userRepository->wasCalled());
    }


    private function makeCommandHandler(): CreateUserCommandHandler
    {
        $container = $this->getContainer();
        $connection = $container
            ->bind(Connection::class,  PdoConnectionDriver::getInstance(SqlLiteConfig::DSN))
            ->get(Connection::class);

        return new CreateUserCommandHandler($this->makeUsersRepository(), $connection);
    }

    private function makeUserCommand(): EntityCommand
    {
        return new EntityCommand($this->getTestUser());
    }

    private function makeUsersRepository(): UserRepositoryInterface
    {
        return  new class implements UserRepositoryInterface {

            private bool $called = false;

            public function findById(int $id): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getUserByEmail(string $email): User
            {
                $this->called = true;
                return new User(
                    'Georgii',
                    'Fadeev',
                    'fadeev123@start2play.ru',
                    '12345678'
                );
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };
    }

    private function getTestUser(): User
    {
        return new User(
            'Georgii',
            'Fadeev',
            'fadeev1220@start2play.ru',
            '12345678'
        );
    }
}