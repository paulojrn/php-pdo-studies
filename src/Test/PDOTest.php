<?php

namespace Alura\Pdo\Test;

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Model\Entity\StudentEntity;
use Alura\Pdo\Model\Repository\StudentRepository;
use DateTimeImmutable;
use PDOException;

class PDOTest
{
    public static function testCreateTables(): void
    {
        $pdo = ConnectionCreator::sqliteConnectionCreate();

        echo "Criando..." . PHP_EOL;

        // $pdo->exec("CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXT);");

        $createTableSql = '
            CREATE TABLE IF NOT EXISTS students (
                id INTEGER PRIMARY KEY,
                name TEXT,
                birth_date TEXT
            );

            CREATE TABLE IF NOT EXISTS phones (
                id INTEGER PRIMARY KEY,
                area_code TEXT,
                number TEXT,
                student_id INTEGER,
                FOREIGN KEY(student_id) REFERENCES students(id)
            );
        ';

        $pdo->exec($createTableSql);
    }

    public static function testGetValues(string $repoName, bool $hydrate): array
    {
        $studentRepository = new $repoName();
        $result = $studentRepository->setHydrate($hydrate)->all();

        var_dump("=== SEARCH ALL ===");
        var_dump($result);

        return $result;
    }

    public static function testGetValue(bool $hydrate): array
    {
        $studentRepository = new StudentRepository();
        $result = $studentRepository->setHydrate($hydrate)->one(6);

        
        var_dump("=== SEARCH BY ID ===");
        var_dump($result);

        return $result;
    }

    public static function testGetValuesByDate(bool $hydrate): void
    {
        $studentRepository = new StudentRepository();
        
        $result = $studentRepository->setHydrate($hydrate)->getStudentsBirthAt(new DateTimeImmutable("1986-07-01"));

        var_dump("=== SEARCH BY DATE ===");
        var_dump($result);
    }

    public static function testSaveObj(): void
    {
        $student = new StudentEntity(null, "Amarildo Jonas", new DateTimeImmutable("1894-01-30"));
        $studentRepository = new StudentRepository();

        $result = $studentRepository->save($student);

        var_dump("=== SAVE: $result ===");
    }

    public static function testUpdateObj(): void
    {
        $student = current(self::testGetValue(true));
        $student->birthDate(new DateTimeImmutable("1986-07-01"));

        $studentRepository = new StudentRepository();

        $result = $studentRepository->save($student);

        var_dump("=== UPDATE: $result ===");
    }

    public static function testRemoveObj(): void
    {
        $studentRepository = new StudentRepository();
        
        $result = $studentRepository->delete(9);

        var_dump("=== DELETE: $result ===");
    }

    public static function testAddStudentsToClass(): void
    {
        $connection = ConnectionCreator::sqliteConnectionCreate();
        $studentRepo = new StudentRepository($connection);

        $connection->beginTransaction();

        try {
            $studentA = new StudentEntity(null, "Alice Maras", new DateTimeImmutable("2008-11-10"));
            $studentB = new StudentEntity(null, "Bernardo Heik", new DateTimeImmutable("1944-06-04"));
            $studentC = new StudentEntity(null, "Carlos Saraiga", new DateTimeImmutable("1999-03-12"));

            $studentRepo->save($studentA);
            $studentRepo->save($studentB);
            $studentRepo->save($studentC);

            $connection->commit();
        } catch (PDOException $exception) {
            var_dump($exception->getMessage());

            $connection->rollBack();
        }        
    }

    public static function testSavePhone(): void
    {
        $pdo = ConnectionCreator::sqliteConnectionCreate();
        $pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('24', '999999999', 4),('21', '222222222', 4);");

    }

    public static function testGetStudentsWithPhones()
    {
        $studentRepository = new StudentRepository();
        $result = $studentRepository->getStudentsWithPhones();
        $studentId = 4;

        var_dump($result);
        var_dump($result[$studentId]->phone()[0]->formattedPhone());
    }
}
