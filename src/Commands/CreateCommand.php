<?php

namespace App\Commands;

use App\Entities\EntityInterface;
use App\Exceptions\CommandException;
use APP\Managers\EntityManagerInterface;

class CreateCommand
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager = null)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(EntityInterface $entity): void
    {
        if ($entity->getId()) {
            throw new CommandException(sprintf("%s already exists", $entity::class));
        }

        $this->entityManager->create($entity);
    }
}