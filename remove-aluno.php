<?php

require "vendor/autoload.php";

$databasePath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$databasePath");

$sql = "DELETE FROM students WHERE id = :id;";
$prep = $pdo->prepare($sql);
$prep->bindValue(":id", 1, PDO::PARAM_INT);
var_dump($prep->execute());
