<?php

namespace App\Factories;

use App\Drivers\Connection;
use App\Entities\User\User;
use App\Repositories\EntityRepositoryInterface;
use App\Repositories\UserRepository;

class RepositoryFactory implements RepositoryFactoryInterface
{
    public function __construct(private Connection $connection){}

    public function create(string $entityType): EntityRepositoryInterface
    {
        return match ($entityType)
        {
            User::class => new UserRepository($this->connection),
        };
    }

}