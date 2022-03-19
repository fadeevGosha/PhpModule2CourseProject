<?php

namespace App\Managers;

use App\Connections\ConnectorInterface;
use App\Connections\SqlLiteConnector;
use App\Drivers\Connection;
use App\Entities\EntityInterface;
use App\Exceptions\NotFoundException;
use ReflectionClass;

class EntityManager implements EntityManagerInterface
{
    private Connection $connection;

    public function __construct(ConnectorInterface $connector = null)
    {
        $connector = $connector ?? new SqlLiteConnector();
        $this->connection = $connector->getConnection();
    }

    /**
     * @throws \ReflectionException
     * @throws NotFoundException
     */
    public function create(EntityInterface $entity)
    {
        if($entity->getId())
        {
            throw new NotFoundException('Невозможно добавить существующий объект');
        }

        $propertiesNames = $this->getPropertyNamesByEntity($entity);

        if(($key = array_search('id', $propertiesNames)) !== false){
            unset($propertiesNames[$key]);
        }

        $propertyKeys = ':'.implode(', :', $propertiesNames);

        $params = [];

        foreach ($propertiesNames as $key)
        {
            $reflectionProperty = new \ReflectionProperty($entity::class, $key);
            $params[ ':'.$key] = $reflectionProperty->getValue($entity);
        }

        $this->connection->executeQuery(
            sprintf('INSERT INTO %s (%s)  VALUES (%s)',
                $entity->getTableName(),
                implode(',', $propertiesNames),
                $propertyKeys,
            ),
            $params
        );
    }

    public function delete(EntityInterface $entity)
    {
        if($entity->getId())
        {
            throw new NotFoundException('Невозможно удалить несуществующий объект');
        }
        $this->connection->executeQuery(
            sprintf('delete from %s where id = :id', $entity->getTableName()),
            ['id' => $entity->getId()]
        );
    }

    /**
     * @throws \ReflectionException
     */
    private function getPropertyNamesByEntity(EntityInterface $entity): array
    {
        $propertiesNames = [];
        $reflectionClass = new ReflectionClass($entity::class);

        foreach ($reflectionClass->getProperties() as $property)
        {
            $propertiesNames[] = $property->getName();
        }

        return $propertiesNames;
    }
}