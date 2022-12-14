<?php

namespace App\Domain\User\Models;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'users')]
final class User implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $visitorId;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    #[Column(name: 'last_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $lastVisit;

    /** @var Collection<int, User> */
    #[OneToMany(mappedBy: 'user', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->firstVisit = new DateTimeImmutable('now');
        $this->lastVisit = new DateTimeImmutable('now');
    }

    public function getId(): int { return $this->id; }
    public function getVisitorId(): string { return $this->visitorId; }
    public function getFirstVisit(): DateTimeImmutable { return $this->firstVisit; }
    public function getLastVisit(): DateTimeImmutable { return $this->lastVisit; }
    public function getUsers(): Collection { return $this->users; }

    public function setVisitorId(string $visitorId): void { $this->visitorId = $visitorId; }
    public function setFirstVisit(DateTimeImmutable $date): void { $this->firstVisit = $date; }
    public function setLastVisit(DateTimeImmutable $date): void { $this->lastVisit = $date; }
    public function setUsers(Collection $users): void { $this->users = $users; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'visitorId' => $this->visitorId,
            'firstVisit' => $this->firstVisit,
            'lastVisit' => $this->lastVisit,
            'users' => $this->users
        );
    }
}
