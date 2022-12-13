<?php

namespace App\Domain\User\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'users')]
final class User implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $identifier;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    #[Column(name: 'last_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $lastVisit;

    public function __construct()
    {
        $this->firstVisit = new DateTimeImmutable('now');
        $this->lastVisit = new DateTimeImmutable('now');
    }

    public function getId(): int { return $this->id; }
    public function getIdentifier(): string { return $this->identifier; }
    public function getFirstVisit(): DateTimeImmutable { return $this->firstVisit; }
    public function getLastVisit(): DateTimeImmutable { return $this->lastVisit; }

    public function setIdentifier(string $identifier): void { $this->identifier = $identifier; }
    public function setFirstVisit(DateTimeImmutable $date): void { $this->firstVisit = $date; }
    public function setLastVisit(DateTimeImmutable $date): void { $this->lastVisit = $date; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'identifier' => $this->identifier,
            'firstVisit' => $this->firstVisit,
            'lastVisit' => $this->lastVisit
        );
    }
}
