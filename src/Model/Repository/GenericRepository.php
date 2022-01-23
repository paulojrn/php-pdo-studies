<?php

namespace Alura\Pdo\Model\Repository;

use Alura\Pdo\Model\Entity\EntityInterface;
use Alura\Pdo\Utils\Utils;
use DateTimeImmutable;
use PDO;
use PDOStatement;
use ReflectionClass;
use ReflectionProperty;

abstract class GenericRepository
{
    protected PDO $pdo;
    protected string $entityName;
    protected bool $hidrate = false;

    /**
     * Construtor
     * 
     * @param string $entityName;
     * @param PDO $pdo;
     */
    abstract public function __construct(string $entityName, PDO $pdo = null);

    /**
     * @param $dataList
     * @return array
     */
    abstract protected function hydrate(array $dataList): array;

    /**
     * Informa se o resultado da pesquisa é na forma de array de arrays ou array de objetos hidratados
     * 
     * @param bool $hidrate
     * @return GenericRepository
     */
    public function setHydrate(bool $hidrate = true): GenericRepository
    {
        $this->hidrate = $hidrate;
        return $this;
    }

    /**
     * @param int $id
     * @return array
     */
    protected function oneNonHidrate(int $id, string $idName): array
    {
        $results = [];
        $table = $this->entityName::TABLE_NAME;

        $statement = $this->pdo->query("SELECT * FROM $table WHERE $idName = $id");

        if ($statement instanceof PDOStatement) {
            $resultAux = $statement->fetch(PDO::FETCH_ASSOC);

            if (is_array($resultAux)) {
                $results = $resultAux;
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    protected function allNonHidrate(): array
    {
        $results = [];
        $table = $this->entityName::TABLE_NAME;

        $statement = $this->pdo->query("SELECT * FROM $table");

        if ($statement instanceof PDOStatement) {
            $resultAux = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (is_array($resultAux)) {
                $results = $resultAux;
            }
        }

        return $results;
    }

    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function save(EntityInterface $entity): bool
    {
        $propertiesMap = $this->mapPropertiesValues($entity);

        if (is_null($propertiesMap["id"])) {
            $sql = $this->createInsertSql($propertiesMap);
        } else {
            $sql = $this->createUpdateSql($propertiesMap);
        }
        
        $statement = $this->pdo->prepare($sql);

        foreach ($propertiesMap as $column => $value) {
            if ($value instanceof DateTimeImmutable) {
                $value = $value->format("Y-m-d");
            }

            $statement->bindValue(":$column", $value);
        }

        $return = $statement->execute();

        var_dump("NEW ID: " . $this->pdo->lastInsertId());

        return $return;
    }

    /**
     * @param int $id
     * @param string $idName = "id"
     * @return bool
     */
    public function delete(int $id, string $idName = "id"): bool
    {
        $sql = $this->createDeleteSql($idName);
        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(":$idName", $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * @param EntityInterface $entity
     * return array
     */
    private function mapPropertiesValues(EntityInterface $entity): array
    {
        /**
         * @var ReflectionProperty $prop
         */

        $reflection = new ReflectionClass($this->entityName);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        $propertiesMap = [];

        foreach ($properties as $prop) {
            $entityPropertyName = $prop->getName();
            $entityPropertyCamelName = Utils::strCamelCaseToSnakeCase($entityPropertyName);

            $value = $entity->{$entityPropertyCamelName}();
            $propertiesMap[$entityPropertyName] = $value;
        }

        return $propertiesMap;
    }

    /**
     * @param array $propertiesMap
     * return string
     */
    private function createInsertSql(array $propertiesMap): string
    {
        $keys = array_keys($propertiesMap);
        $columns = implode(',', $keys);
        $values = implode(',', array_map(fn ($key) => ":$key", $keys));
        $table = $this->entityName::TABLE_NAME;

        return "INSERT INTO $table ($columns) VALUES ($values);";
    }

    /**
     * @param array $propertiesMap
     * return string
     */
    private function createUpdateSql(array $propertiesMap): string
    {
        $idName = array_key_first($propertiesMap);
        unset($propertiesMap[$idName]);
        $keys = array_keys($propertiesMap);
        $values = implode(',', array_map(fn ($key) => "$key = :$key", $keys));
        $table = $this->entityName::TABLE_NAME;

        return "UPDATE $table SET $values WHERE $idName = :$idName;";
    }

    /**
     * @param string $idName
     * return string
     */
    private function createDeleteSql(string $idName): string
    {
        $table = $this->entityName::TABLE_NAME;

        return "DELETE FROM $table WHERE $idName = :$idName;";
    }
}
