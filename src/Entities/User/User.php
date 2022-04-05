<?php

namespace App\Entities\User;

class User implements UserInterface
{
    public const TABLE_NAME = 'User';

    private ?int $id = null;
    private string $password;

    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
    ) {}

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    private static function hash(string $password, int $id): string
    {
        return hash('sha256', $id . $password);
    }

    public function checkPassword(string $password): bool
    {
        return $this->password === self::hash($password, $this->id);
    }

    public function __toString(): string
    {
        return sprintf(
            "[%d] %s %s %s",
            $this->getId(),
            $this->getFirstName(),
            $this->getLastName(),
            $this->getEmail()
        );
    }


    public function getTableName(): string
    {
        return static::TABLE_NAME;
    }
}