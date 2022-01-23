<?php

namespace Alura\Pdo\Model\Entity;

use DateTimeInterface;
use DateTimeImmutable;

class StudentEntity implements EntityInterface
{
    const TABLE_NAME = "students";

    private ?int $id;
    private string $name;
    private DateTimeInterface $birth_date;

    public function __construct(?int $id, string $name, DateTimeInterface $birthDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birth_date = $birthDate;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(string $name = null): string
    {
        if (!is_null($name)) {
            $this->name = $name;
        }

        return $this->name;
    }

    public function birthDate(DateTimeInterface $birthDate = null): DateTimeInterface
    {
        if (!is_null($birthDate)) {
            $this->birth_date = $birthDate;
        }

        return $this->birth_date;
    }

    public function age(): int
    {
        return $this->birth_date->diff(new DateTimeImmutable())->y;
    }
}
