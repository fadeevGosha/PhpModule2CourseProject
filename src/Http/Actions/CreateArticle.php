<?php

namespace App\Http\Actions;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\EntityCommand;
use App\Container\DIContainer;
use App\Entities\Article\Article;
use App\Exceptions\CommandException;
use App\Exceptions\HttpException;
use App\Exceptions\UserNotFoundException;
use App\Http\Auth\IdentificationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private CreateArticleCommandHandler $articleCommandHandler,
        private IdentificationInterface $identification
    )
    {}

    public function handle(Request $request): Response
    {
        $logger = DIContainer::getInstance()->get(LoggerInterface::class);

        try {
            $author = $this->identification->getUser($request);

            $article = new Article(
                $author->getId(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );

            $this->articleCommandHandler->handle(new EntityCommand($article));
        } catch (HttpException|CommandException|UserNotFoundException $exception) {
            $message = $exception->getMessage();
            $logger->error($exception);
            return new ErrorResponse($message);
        }
        $data = [
            'authorId' => $article->getAuthorId(),
            'title' => $article->getTitle(),
            'text' => $article->getText()
        ];

        $logger->info('Created new Article', $data);
        return new SuccessfulResponse($data);
    }
}