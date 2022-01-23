<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require "vendor/autoload.php";

$pdo = ConnectionCreator::sqliteConnectionCreate();

$sql = "DELETE FROM students WHERE id = :id;";
$prep = $pdo->prepare($sql);
$prep->bindValue(":id", 1, PDO::PARAM_INT);

var_dump($prep->execute());
