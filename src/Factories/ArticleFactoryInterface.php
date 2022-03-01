<?php

namespace App\Factories;

use App\Entity\Article\ArticleInterface;

interface ArticleFactoryInterface extends FactoryInterface
{
    public function create(): ArticleInterface;
}