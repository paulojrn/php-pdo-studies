<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

$databasePath = __DIR__ . "/banco.sqlite";
$pdo = ConnectionCreator::sqliteConnectionCreate();

echo "Conectado" . PHP_EOL;

// $pdo->exec("CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXT);");