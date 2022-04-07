<?php

namespace App\Commands;

use App\Drivers\Connection;

class DeleteArticleCommandHandler implements CommandHandlerInterface
{
    public function __construct(private Connection $connection){}

    /**
     * @var EntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        $article = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute([':id' => $article->getId()]);
    }

    public function getSQL(): string
    {
        return "DELETE FROM Article WHERE id = :id";
    }
}
