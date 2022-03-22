<?php

namespace Tests\Commands;

use App\Commands\CreateEntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\Drivers\PdoConnectionDriver;
use App\Entities\User\User;
use App\Exceptions\UserEmailExistException;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $pdoConnectionMock = $this->createStub(PdoConnectionDriver::class);
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock->method('getUserByEmail')->willReturn(
            new User('Georgii', 'Fadeev', 'fadeev@start2play.ru')
        );

        $userRepositoryMock->method('executeReturn')->willReturn(
            new User('Georgii', 'Fadeev', 'fadeev@start2play.ru')
        );


        $createUserCommandHandler = new CreateUserCommandHandler(
            $userRepositoryMock,
            $pdoConnectionMock,
        );

        $this->expectException(UserEmailExistException::class);
        $this->expectExceptionMessage('Пользователь с таким email уже существует в системе');

        $command = new CreateEntityCommand(
            new User(
                'Georgii',
                'Fadeev',
                'fadeev123@start2play.ru'
            )
        );

        $createUserCommandHandler->handle($command);

    }
}