<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Repositories\MemoryUserRepository;

class RepositoryFactory implements RepositoryFactoryInterface
{
    public function create(EntityInterface $entity)
    {
        return match ($entity::class)
        {
            User::class => new MemoryUserRepository()
        };
    }
}