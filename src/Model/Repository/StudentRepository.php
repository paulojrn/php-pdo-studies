<?php

namespace Alura\Pdo\Model\Repository;

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use DateTimeImmutable;
use DateTimeInterface;
use PDO;

class StudentRepository extends GenericRepository
{
    /**
     * @inheritdoc
     */
    public function __construct(string $entityName, ?PDO $pdo = null)
    {
        if (is_null($pdo)) {
            $pdo = ConnectionCreator::sqliteConnectionCreate();
        }
        
        $this->pdo = $pdo;
        $this->entityName = $entityName;
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
     * @return array
     */
    public function all(): array
    {   
        $results = $this->allNonHidrate();

        if ((count($results) > 0) && $this->hidrate) {
            $results = $this->hydrate($results);
        }

        return $results;
    }

    /**
     * @param int $id
     * @return array
     */
    public function one(int $id, string $idName = "id"): array
    {
        $results = $this->oneNonHidrate($id, $idName);

        if ((count($results) > 0) && $this->hidrate) {
            $results = $this->hydrate([$results]);
        }

        return $results;
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
}
