<?php

namespace App\Commands\SymfonyCommands;

use App\Commands\CreateArticleCommandHandler;
use App\Commands\CreateUserCommandHandler;
use App\Commands\EntityCommand;
use App\Entities\Article\Article;
use App\Entities\User\User;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private Generator $faker,
        private CreateUserCommandHandler $createUserCommandHandler,
        private CreateArticleCommandHandler $articleCommandHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getEmail());
        }

        foreach ($users as $user) {
            for ($i = 0; $i < 20; $i++) {
                $article = $this->createFakeArticle($user);
                $output->writeln('Article created: ' . $article->getTitle());
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user =
            new User(
                $this->faker->firstName,
                $this->faker->lastName,
                $this->faker->userName,
                $this->faker->password,
            );


        $userId = $this->createUserCommandHandler->handle(new EntityCommand($user));
        $user->setId($userId);
        return $user;
    }

    private function createFakeArticle(User $author): Article
    {
        $article = new Article(
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );

        $this->articleCommandHandler->handle(new EntityCommand($article));
        return $article;
    }

}