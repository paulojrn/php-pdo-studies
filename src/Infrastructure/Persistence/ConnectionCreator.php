<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    /**
     * @param PDO $connection
     * @return PDO
     */
    private static function configs(PDO $connection): PDO
    {
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }

    /**
     * @return PDO
     */
    public static function sqliteConnectionCreate(): PDO
    {
        $databasePath = __DIR__ . "/banco.sqlite";

        $connection = new PDO("sqlite:$databasePath");

        return self::configs($connection);
    }

    public static function mysqlConnectionCreate(): PDO
    {
        $connection = new PDO(
            "mysql:host=localhost;bdname=banco",
            "root",
            "senhasenha"
        );

        return self::configs($connection);
    }
}
