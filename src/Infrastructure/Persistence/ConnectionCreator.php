<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    /**
     * @return PDO
     */
    public static function sqliteConnectionCreate(): PDO
    {
        $databasePath = __DIR__ . "/banco.sqlite";

        return new PDO("sqlite:$databasePath");
    }
}
