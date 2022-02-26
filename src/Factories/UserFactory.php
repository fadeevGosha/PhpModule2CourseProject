<?php

namespace App\Factories;

use App\User\User;

final class UserFactory extends Factory
{
    public function create(): User
    {
        return new User(
            self::$facker->randomDigitNot(0),
            self::$facker->firstName(),
            self::$facker->lastName(),
        );
    }
}