<?php

namespace App\Domain\User\Models;

use App\Base\BaseModel;
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
class User extends BaseModel implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $visitorId;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    #[Column(name: 'last_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $lastVisit;

    /** @var Collection<int, Event> */
    #[OneToMany(mappedBy: 'owner', targetEntity: Event::class)]
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
    public function getVisitorId(): string { return $this->visitorId; }
    public function getFirstVisit(): DateTimeImmutable { return $this->firstVisit; }
    public function getLastVisit(): DateTimeImmutable { return $this->lastVisit; }
    public function getOwnedEvents(): Collection { return $this->ownedEvents; }
    public function getEvents(): Collection { return $this->events; }

    public function setVisitorId(string $visitorId): void { $this->visitorId = $visitorId; }
    public function setFirstVisit(DateTimeImmutable $date): void { $this->firstVisit = $date; }
    public function setLastVisit(DateTimeImmutable $date): void { $this->lastVisit = $date; }
    public function setOwnedEvents(Collection $ownedEvents): void { $this->ownedEvents = $ownedEvents; }
    public function setEvents(Collection $events): void { $this->events = $events; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'visitorId' => $this->visitorId,
            'firstVisit' => $this->firstVisit,
            'lastVisit' => $this->lastVisit,
            'ownedEvents' => $this->ownedEvents,
            'events' => $this->events
        );
    }
}
