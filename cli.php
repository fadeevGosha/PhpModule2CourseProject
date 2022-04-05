<?php

use App\Commands\CommandHandlerInterface;
use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateCommentCommandHandler;
use App\Commands\EntityCommand;
use App\Commands\CreateUserCommandHandler;
use App\Container\DIContainer;
use App\Entities\Article\Article;
use App\Entities\Comment\Comment;
use App\Entities\User\User;
use App\Enums\Argument;
use App\Exceptions\NotFoundException;
use App\Factories\EntityManagerFactory;
use App\Factories\EntityManagerFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @var DIContainer $container
 */
if(isset($container)) {
    /**
     * @var LoggerInterface $logger
     */
    $logger = $container->get(LoggerInterface::class);

    try {
        if (count($argv) < 2) {
            throw new NotFoundException('404');
        }

        if (!in_array($argv[1], Argument::getArgumentValues())) {
            throw new NotFoundException('404');
        }
        /**
         * @var EntityManagerFactoryInterface $entityMangerFactory
         */
        $entityMangerFactory = new EntityManagerFactory();
        $entity = $entityMangerFactory->createEntityByInputArguments($argv);


        /**
         * @var CommandHandlerInterface $commandHandler
         */
        $commandHandler = match ($entity::class) {
            Article::class => $container->get(CreateArticleCommandHandler::class),
            Comment::class => $container->get(CreateCommentCommandHandler::class),
            User::class => $container->get(CreateUserCommandHandler::class)
        };

        $commandHandler->handle(new EntityCommand($entity));


    } catch (Exception $exception) {
        $logger->error($exception->getMessage(), ['exception' => $exception]);

        echo $exception->getMessage() . PHP_EOL;
        http_response_code(404);
    }
}