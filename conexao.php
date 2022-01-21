<?php

$pathSqlite = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$pathSqlite");

echo "Conectado" . PHP_EOL;