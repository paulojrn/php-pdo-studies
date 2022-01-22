<?php

use Alura\Pdo\Domain\Model\Student;

require "vendor/autoload.php";

$databasePath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$databasePath");

$student = new Student(null, "Luciana Matos", new DateTimeImmutable("2000-12-25"));

// $sqlInsert = "INSERT INTO students (name, birth_date) VALUES ('{$student->name()}', '{$student->birthDate()->format('Y-m-d')}')";

// $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ?);";
// $statement = $pdo->prepare($sqlInsert);
// $statement->bindValue(1, $student->name());
// $statement->bindValue(2, $student->birthDate()->format("Y-m-d"));
// $result = $statement->execute();

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(":name", $student->name());
$statement->bindValue(":birth", $student->birthDate()->format("Y-m-d"));
$result = $statement->execute();

echo "INSERT:" . $sqlInsert . PHP_EOL;

// $result = $pdo->exec($sqlInsert);

var_dump($result);