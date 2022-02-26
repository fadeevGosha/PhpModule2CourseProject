<?php

namespace App\Types;

class CliArgumentTypes
{
    public const USER = 'user';
    public const ARTICLE = 'article';
    public const COMMENT = 'comment';

    public const TYPES = [
        self::USER,
        self::ARTICLE,
        self::COMMENT,
    ];
}