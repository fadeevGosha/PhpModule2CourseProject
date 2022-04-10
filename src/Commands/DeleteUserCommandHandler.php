<?php

namespace App\Commands;

use App\Drivers\Connection;

class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(private Connection $connection){}

    /**
     * @var DeleteEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $id = $command->getId();
        $this->connection->prepare($this->getSQL())->execute([':id' => $id]);
    }

    public function getSQL(): string
    {
        return "DELETE FROM User WHERE id = :id";
    }
}
