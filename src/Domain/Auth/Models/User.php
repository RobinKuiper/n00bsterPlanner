<?php

namespace App\Domain\Auth\Models;

use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\Necessity;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;

#[Entity, Table(name: 'users')]
class User implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

//    #[Column(type: 'string', unique: true, nullable: false)]
//    private string $visitorId;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $username;

    #[Column(type: 'string', unique: false, nullable: false)]
    private string $password;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    /** @var Collection<int, UserSession> */
    #[OneToMany(mappedBy: 'user', targetEntity: UserSession::class, cascade: ['persist'])]
    private Collection $sessions;

    /** @var Collection<int, Event> */
    #[OneToMany(mappedBy: 'ownedBy', targetEntity: Event::class)]
    private Collection $ownedEvents;

    /** @var Collection<int, Event> */
    #[ManyToMany(targetEntity: Event::class)]
//    #[JoinTable(name: 'users_events')]
    private Collection $events;

    /** @var Collection<int, Necessity> */
    #[OneToMany(mappedBy: 'member', targetEntity: Necessity::class)]
    private Collection $necessities;

    public function __construct()
    {
        $this->firstVisit = new DateTimeImmutable('now');

        $this->sessions = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->ownedEvents = new ArrayCollection();
        $this->necessities = new ArrayCollection();
    }

    public function getId(): int { return $this->id; }

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }

    public function setPassword(string $password): void { $this->password = $password; }
    public function getPassword(): string { return $this->password; }

    public function getFirstVisit(): DateTimeImmutable { return $this->firstVisit; }
    public function setFirstVisit(DateTimeImmutable $date): void { $this->firstVisit = $date; }

    public function getOwnedEvents(): Collection { return $this->ownedEvents; }
    public function setOwnedEvents(Collection $ownedEvents): void { $this->ownedEvents = $ownedEvents; }

    public function getEvents(): Collection { return $this->events; }
    public function setEvents(Collection $events): void { $this->events = $events; }

    public function getNecessities(): Collection { return $this->necessities; }
    public function setNecessities(Collection $necessities): void { $this->necessities = $necessities; }

    public function addSession(UserSession $session)
    {
        if(!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setUser($this);
        }
    }

    public function getAllEvents(): Collection
    {
        $events = $this->events->toArray();
        $ownedEvents = $this->ownedEvents->toArray();

        return new ArrayCollection(
            array_merge($events, $ownedEvents)
        );
    }

    public function addEvent(Event $event)
    {
        if(!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addMember($this);
        }
    }

    public function removeEvent(Event $event)
    {
        if($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeMember($this);
        }
    }

    public function addOwnedEvent(Event $event)
    {
        if(!$this->ownedEvents->contains($event)) {
            $this->ownedEvents->add($event);
            $event->setOwnedBy($this);
        }
    }

    public function removeOwnedEvent(Event $event)
    {
        if($this->ownedEvents->contains($event)) {
            $this->ownedEvents->removeElement($event);
            // Todo: Remove event?
        }
    }

    public function addNecessity(Necessity $necessity)
    {
        if(!$this->necessities->contains($necessity)) {
            $this->necessities->add($necessity);
            $necessity->setMember($this);
        }
    }

    public function removeNecessity(Necessity $necessity)
    {
        if($this->necessities->contains($necessity)) {
            $this->necessities->removeElement($necessity);
            $necessity->setMember(null);
        }
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->username,
            'firstVisit' => $this->firstVisit,
            'ownedEvents' => $this->ownedEvents,
            'events' => $this->events,
            'necessities' => $this->necessities,
        );
    }
}
