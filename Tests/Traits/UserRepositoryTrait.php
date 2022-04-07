<?php

namespace Tests\Traits;

use App\Entities\User\User;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;

trait UserRepositoryTrait
{
    private function makeUsersRepository(): UserRepositoryInterface
    {
        return new class implements UserRepositoryInterface {

            public function findById(int $id): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getUserByEmail(string $email): User
            {
                return new User('name', 'name', 'fadeev123@start2play.ru', '12345678');
            }
        };
    }
}