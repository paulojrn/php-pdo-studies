<?php

namespace Alura\Pdo\Model\Repository;

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Model\Entity\PhoneEntity;
use PDO;

class PhoneRepository extends GenericRepository
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
        $this->entityName = PhoneEntity::class;
    }

    /**
     * @inheritdoc
     */
    protected function hydrate(array $dataList): array
    {
        $phoneList = [];

        foreach ($dataList as $data) {
            $phoneList[] = new $this->entityName(
                $data["id"],
                $data["area_code"],
                $data["number"]
            );
        }

        return $phoneList;
    }
}
