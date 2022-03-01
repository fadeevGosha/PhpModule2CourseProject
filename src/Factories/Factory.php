<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use Faker\Generator;
use \Faker\Factory as FakerFactory;

abstract class Factory implements FactoryInterface
{
    protected  ?Generator $faker = null;

    public function __construct(Factory $faker = null) {
        $this->faker = $facker ?? FakerFactory::create();
    }

    abstract public function create():EntityInterface;
}