<?php

namespace App\Http\Actions;

use App\Commands\CreateEntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\Entities\User\User;
use App\Exceptions\HttpException;
use App\Exceptions\UserEmailExistException;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;

class CreateUser implements ActionInterface
{
    public function __construct(
        private ?UserRepositoryInterface $usersRepository = null,
        private ?CreateUserCommandHandler $createUserCommandHandler = null
    ) {
        $this->usersRepository = $this->usersRepository ?? new UserRepository();
        $this->createUserCommandHandler = $this->createUserCommandHandler ?? new CreateUserCommandHandler($this->usersRepository);
    }

    public function handle(Request $request): Response
    {
        try {
            $user = new User(
                $request->jsonBodyField('firstName'),
                $request->jsonBodyField('lastName'),
                $request->jsonBodyField('email'),
            );

            $this->createUserCommandHandler->handle(new CreateEntityCommand($user));
        }catch (HttpException|UserEmailExistException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessfulResponse([
            'email' => $user->getEmail(),
        ]);
    }
}