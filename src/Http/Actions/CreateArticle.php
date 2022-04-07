<?php

namespace App\Http\Actions;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\EntityCommand;
use App\Container\DIContainer;
use App\Entities\Article\Article;
use App\Http\Auth\TokenAuthenticationInterface;
use App\Http\ErrorResponse;
use App\Http\Request;
use App\Http\Response;
use App\Http\SuccessfulResponse;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private CreateArticleCommandHandler $articleCommandHandler,
        private TokenAuthenticationInterface $authentication
    ){}

    public function handle(Request $request): Response
    {
        $logger = DIContainer::getInstance()->get(LoggerInterface::class);

        try {
            $article = new Article(
                $this->authentication->getUser($request),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );

            $article = $this->articleCommandHandler->handle(new EntityCommand($article));
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            $logger->error($exception);
            return new ErrorResponse($message);
        }

        $data = [
            'id' => $article->getId(),
            'author' => $article->getAuthor()->getId(),
            'title' => $article->getTitle(),
            'text' => $article->getText()
        ];

        $logger->info('Created new Article', $data);
        return new SuccessfulResponse($data);
    }
}