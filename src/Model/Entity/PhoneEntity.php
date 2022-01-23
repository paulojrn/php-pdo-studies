<?php

namespace Alura\Pdo\Model\Entity;

class PhoneEntity implements EntityInterface
{
    const TABLE_NAME = "phones";

    private ?int $id;
    private string $area_code;
    private string $number;

    public function __construct(?int $id, string $areaCode, string $number)
    {
        $this->id = $id;
        $this->area_code = $areaCode;
        $this->number = $number;
    }

    public function formattedPhone(): string
    {
        return "({$this->area_code}) {$this->number}";
    }
}
