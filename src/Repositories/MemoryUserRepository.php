<?php

namespace App\Repositories;

use App\Entities\User\User;

class MemoryUserRepository implements MemoryUserRepositoryInterface
{
    protected array $users;

    public function save(User $user)
    {
        $this->users[$user->getId()] = $user;
    }

    public function get(int $id):User
    {
        return $this->users[$id];
    }
}


