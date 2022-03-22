<?php

namespace App\Repositories;

use App\Connections\ConnectorInterface;
use App\Drivers\Connection;
use App\Entities\EntityInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    protected ConnectorInterface $connector;
    protected  Connection $connection;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
        $this->connection =  $this->connector->getConnection();
    }

    abstract public function save(EntityInterface $entity):void;
    abstract public function get(int  $id): EntityInterface;
}