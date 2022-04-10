<?php

namespace App\Commands;

use App\Drivers\Connection;
use App\Entities\Article\Article;

class DeleteArticleCommandHandler implements CommandHandlerInterface
{
    public function __construct(private Connection $connection){}

    /**
     * @var DeleteEntityCommand $command
     */
    public function handle(CommandInterface $command): void
    {
        /**
         * @var EntityCommand $command
         */
        $article = $command->getEntity();
        $this->connection->prepare($this->getSQL())->execute([':id' => $article->getId()]);
    }


    public function getSQL(): string
    {
        return "DELETE FROM Article WHERE id = :id";
    }
}
