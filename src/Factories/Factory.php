<?php


namespace App\Factories;


use Faker\Generator;

abstract class Factory
{
    protected static ?Generator $facker = null;
    private static array $instances = [];

    protected function __construct() {
        self::$facker = \Faker\Factory::create();
    }

    public static function getInstance(): Factory
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    abstract public function create();
}