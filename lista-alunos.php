<?php

use Alura\Pdo\Domain\Model\Student;

require "vendor/autoload.php";

$databasePath = __DIR__ . "/banco.sqlite";
$pdo = new PDO("sqlite:$databasePath");

$statement1 = $pdo->query("SELECT * FROM students");
$statement2 = $pdo->query("SELECT * FROM students WHERE id = 1");

// $studentColumn = $statement1->fetchColumn(1); // procura na coluna 1 (nome) e coloca o cursor na próxima linha
// echo "POR COLUNA" . PHP_EOL;
// var_dump($studentColumn);

// $result = $statement->fetchAll(PDO::FETCH_CLASS, Student::class);
$result = $statement1->fetchAll(PDO::FETCH_ASSOC); // procura e coloca o cursor na próxima linha
echo "ARRAY" . PHP_EOL;
var_dump($result);

// $studentList = [];
// foreach ($result as $item) {
//     $studentList[] = new Student(
//         $item["id"],
//         $item["name"],
//         new DateTimeImmutable($item["birth_date"]));
// }
// echo "ARRAY DE OBJETOS" . PHP_EOL;
// var_dump($studentList);

// $student = $statement2->fetch(PDO::FETCH_ASSOC);
// echo "UM ITEM APENAS" . PHP_EOL;
// var_dump($student);
