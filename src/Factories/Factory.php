<?php

namespace App\Factories;

use App\Entities\EntityInterface;
use Faker\Generator;
use \Faker\Factory as FackerFactory;

abstract class Factory implements FactoryInterface
{
    protected  ?Generator $facker = null;

    public function __construct(Factory $facker = null) {
        $this->facker = $facker ?? FackerFactory::create();
    }

    abstract public function create():EntityInterface;
}