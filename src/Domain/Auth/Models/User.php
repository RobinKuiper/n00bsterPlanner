<?php

namespace App\Domain\Auth\Models;

use App\Domain\Event\Models\Event;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'users')]
class User implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

//    #[Column(type: 'string', unique: true, nullable: false)]
//    private string $visitorId;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $name;

    #[Column(type: 'string', unique: false, nullable: false)]
    private string $password;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    #[Column(name: 'last_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $lastVisit;

    /** @var Collection<int, Event> */
    #[OneToMany(mappedBy: 'owned_by', targetEntity: Event::class)]
    private Collection $ownedEvents;

    /** @var Collection<int, Event> */
    #[ManyToMany(targetEntity: Event::class, mappedBy: 'users')]
    private Collection $events;

    public function __construct()
    {
        $this->firstVisit = new DateTimeImmutable('now');
        $this->lastVisit = new DateTimeImmutable('now');
    }

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getFirstVisit(): DateTimeImmutable { return $this->firstVisit; }
    public function setFirstVisit(DateTimeImmutable $date): void { $this->firstVisit = $date; }

    public function getLastVisit(): DateTimeImmutable { return $this->lastVisit; }
    public function setLastVisit(DateTimeImmutable $date): void { $this->lastVisit = $date; }

    public function getOwnedEvents(): Collection { return $this->ownedEvents; }
    public function setOwnedEvents(Collection $ownedEvents): void { $this->ownedEvents = $ownedEvents; }

    public function getEvents(): Collection { return $this->events; }
    public function setEvents(Collection $events): void { $this->events = $events; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'firstVisit' => $this->firstVisit,
            'lastVisit' => $this->lastVisit,
            'ownedEvents' => $this->ownedEvents,
            'events' => $this->events
        );
    }
}
