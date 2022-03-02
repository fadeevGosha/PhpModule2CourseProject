<?php

namespace App\Factories;

use App\Connections\ConnectorInterface;
use App\Entities\Article\Article;
use App\Entities\EntityInterface;
use App\Entities\User\User;
use App\Repositories\MemoryUserRepository;
use App\Repositories\UserRepository;
use JetBrains\PhpStorm\Pure;

class RepositoryFactory implements RepositoryFactoryInterface
{
    private ConnectorInterface $connector;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    #[Pure] public function create(EntityInterface $entity): EntityInterface
    {
        return match ($entity::class)
        {
            User::class => new UserRepository($this->connector),
            //из вашего дз
        };
    }
}