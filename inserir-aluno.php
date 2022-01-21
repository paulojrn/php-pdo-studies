<?php

use Alura\Pdo\Domain\Model\Student;

require "vendor/autoload.php";

$databasePath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$databasePath");

$student = new Student(null, "Paulo JRN", new DateTimeImmutable("1986-07-01"));
$sqlInsert = "INSERT INTO students (name, birth_date) VALUES ('{$student->name()}', '{$student->birthDate()->format('Y-m-d')}')";

echo "INSERT:" . $sqlInsert . PHP_EOL;

// $rows = $pdo->exec($sqlInsert);

var_dump($rows);