<?php

namespace Alura\Pdo\Model\Repository;

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Model\Entity\PhoneEntity;
use Alura\Pdo\Model\Entity\StudentEntity;
use DateTimeImmutable;
use DateTimeInterface;
use PDO;

class StudentRepository extends GenericRepository
{
    /**
     * @inheritdoc
     */
    public function __construct(?PDO $pdo = null)
    {
        if (is_null($pdo)) {
            $pdo = ConnectionCreator::sqliteConnectionCreate();
        }
        
        $this->pdo = $pdo;
        $this->entityName = StudentEntity::class;
    }

    /**
     * @inheritdoc
     */
    protected function hydrate(array $dataList): array
    {
        $studentList = [];

        foreach ($dataList as $data) {
            $studentList[] = new $this->entityName(
                $data["id"],
                $data["name"],
                new DateTimeImmutable($data["birth_date"]));
        }

        return $studentList;
    }

    /**
     * @param DateTimeInterface $date
     * @return array
     */
    public function getStudentsBirthAt(DateTimeInterface $date): array
    {
        $table = $this->entityName::TABLE_NAME;

        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE birth_date = ?");
        $stmt->bindValue(1, $date->format("Y-m-d"));
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ((count($results) > 0) && $this->hidrate) {
            $results = $this->hydrate($results);
        }

        return $results;
    }

    public function getStudentsWithPhones(): array
    {
        $results = [];

        $sqlQuery = 'SELECT students.id,
                            students.name,
                            students.birth_date,
                            phones.id AS phone_id,
                            phones.area_code,
                            phones.number
                     FROM students
                     JOIN phones ON students.id = phones.student_id;';
        $stmt = $this->pdo->query($sqlQuery);
        $results = $stmt->fetchAll();

        if (is_array($results)) {
            $studentList = [];
            
            foreach ($results as $row) {
                if (!array_key_exists($row['id'], $studentList)) {
                    $studentList[$row['id']] = new StudentEntity(
                        $row['id'],
                        $row['name'],
                        new DateTimeImmutable($row['birth_date'])
                    );
                }

                $phone = new PhoneEntity($row['phone_id'], $row['area_code'], $row['number']);
                $studentList[$row['id']]->phone($phone);
            }

            $results = $studentList;
        }
        
        return $results;
    }
}
