<?php

namespace App\Http\Auth;

use App\Entities\User\User;
use App\Exceptions\AuthException;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Request;
use App\Repositories\UserRepositoryInterface;

class PasswordAuthentication implements AuthenticationInterface
{
    public function __construct(
        private UserRepositoryInterface $usersRepository
    ) {
    }

    public function getUser(Request $request): User
    {
        try {
            $email = $request->jsonBodyField('email');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getUserByEmail($email);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        $hash = hash('sha256', $password);


        if ($hash !== $user->getPassword()) {
            throw new AuthException('Wrong password');
        }

        return $user;
    }
}