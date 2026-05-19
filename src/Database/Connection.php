<?php

namespace Araecy\Framework\Database;
use PDO;
class Connection
{
    private static $instence = null;
    public ?PDO $pdo = null;

    private function __construct(string $connectionString, string $username, string $password)
    {
        $this->pdo = new PDO($connectionString, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function create(string $connectionString, string $username, string $password): static
    {
        if (null === static::$instence) {
            static::$instence = new static($connectionString, $username, $password);
        }

        return static::$instence;
    }

    public static function getConnection(): static
    {
        return static::$instence;
    }
}