<?php

namespace Tests\Commands;

use App\Commands\EntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\Connections\ConnectorInterface;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Tests\Traits\LoggerTrait;
use Tests\Traits\UserRepositoryTrait;

class CreateUserCommandTest extends TestCase
{
    use LoggerTrait;
    use UserRepositoryTrait;

    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $createUserCommandHandler = $this->makeCommandHandler();

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new EntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);
    }


    public function testItThrowsAnExceptionWhenUserAlreadyExistsByAnonymous(): void
    {
        $createUserCommandHandler = $this->makeCommandHandler();

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new EntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);
    }

    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UserRepositoryInterface {

            private bool $called = false;

            public function findById(int $id): EntityInterface
            {
                throw new UserNotFoundException("Not found");
            }

            public function getUserByEmail(string $email): User
            {
                $this->called = true;
                return new User('name', 'name', 'fadeev123@start2play.ru');
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $createUserCommandHandler = $this->makeCommandHandler();

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new EntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);
        $this->assertTrue($usersRepository->wasCalled());
    }

    private function makeCommandHandler(): CreateUserCommandHandler
    {
        return new CreateUserCommandHandler(
            $this->makeUsersRepository(),
            $this->getContainer()->get(ConnectorInterface::class),
            $this->getLogger()
        );
    }
}