<?php

namespace App\Application\Base;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use JsonSerializable;

abstract class BaseModel implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    abstract public function jsonSerialize(): array;
}
