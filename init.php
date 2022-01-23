<?php

require "vendor/autoload.php";

use Alura\Pdo\Model\Repository\PhoneRepository;
use Alura\Pdo\Model\Repository\StudentRepository;
use Alura\Pdo\Test\PDOTest;

$repoName = StudentRepository::class;

PDOTest::testGetValues($repoName, false);

// PDOTest::testGetValues(true);
// PDOTest::testSaveObj();
// PDOTest::testUpdateObj();
// PDOTest::testRemoveObj();
// PDOTest::testGetValue(true);
// PDOTest::testGetValuesByDate(false);
// PDOTest::testAddStudentsToClass();
// PDOTest::testCreateTables();
// PDOTest::testSavePhone();
PDOTest::testGetStudentsWithPhones();

// PDOTest::testGetValues($repoName, false);