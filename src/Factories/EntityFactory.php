<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use App\Types\CliArgumentTypes;

class EntityFactory implements EntityFactoryInterface
{
    private static UserFactoryInterface $userFactory;
    private static ArticleFactoryInterface $articleFactory;
    private static CommentFactoryInterface $commentFactory;
    private static array $instances = [];

    private function __construct(
        UserFactoryInterface $userFactory = null,
        ArticleFactoryInterface $articleFactory = null,
        CommentFactoryInterface $commentFactory = null
    )
    {
        self::$userFactory = $userFactory ?? new UserFactory();
        self::$articleFactory = $articleFactory ?? new ArticleFactory(self::$userFactory);
        self::$commentFactory = $commentFactory ?? new CommentFactory(self::$userFactory, self::$articleFactory);
    }

    public static function getInstance(
        UserFactoryInterface $userFactory = null,
        ArticleFactoryInterface $articleFactory = null,
        CommentFactoryInterface $commentFactory = null
    ): EntityFactoryInterface
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] =
                new static(
                    $userFactory,
                    $articleFactory,
                    $commentFactory
            );
        }

        return self::$instances[$class];
    }

    public function create(string $type): EntityInterface
    {
        return match ($type)
        {
            CliArgumentTypes::USER => self::$userFactory->create(),
            CliArgumentTypes::ARTICLE => self::$articleFactory->create(),
            CliArgumentTypes::COMMENT => self::$commentFactory->create(),
        };
    }
}