<?php

namespace App\Domain\Auth\Models;

use App\Domain\Event\Models\Event;
use App\Domain\Necessity\Models\Necessity;
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

    #[Column(type: 'string', unique: true, nullable: true)]
    private string $visitorId;

    #[Column(type: 'string', unique: true, nullable: true)]
    private string $username;

    #[Column(type: 'string', unique: false, nullable: true)]
    private string $password;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    // One User has multiple UserSessions
    /** @var Collection<int, UserSession> */
    #[OneToMany(mappedBy: 'user', targetEntity: UserSession::class, cascade: ['persist'])]
    private Collection $sessions;

    // One User has multiple OwnedEvents
    /** @var Collection<int, Event> */
    #[OneToMany(mappedBy: 'ownedBy', targetEntity: Event::class, cascade: ['persist'])]
    private Collection $ownedEvents;

    // Many Users have multiple Events
    /** @var Collection<int, Event> */
    #[ManyToMany(targetEntity: Event::class, inversedBy: 'members')]
    #[JoinTable(name: 'users_events')]
    private Collection $events;

    // One User has multiple necessities
    /** @var Collection<int, Necessity> */
    #[OneToMany(mappedBy: 'member', targetEntity: Necessity::class)]
    private Collection $necessities;

    // One User created multiple necessities
    /** @var Collection<int, Necessity> */
    #[OneToMany(mappedBy: 'creator', targetEntity: Necessity::class)]
    private Collection $createdNecessities;

    public function __construct()
    {
        $this->firstVisit = new DateTimeImmutable('now');

        $this->sessions = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->ownedEvents = new ArrayCollection();
        $this->necessities = new ArrayCollection();
        $this->createdNecessities = new ArrayCollection();
    }

    public function getId(): int { return $this->id; }

    public function getVisitorId(): string { return $this->visitorId; }
    public function setVisitorId(string $visitorId): void { $this->visitorId = $visitorId; }

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

    public function getCreatedNecessities(): Collection { return $this->createdNecessities; }
    public function setCreatedNecessities(Collection $necessities): void { $this->createdNecessities = $necessities; }

    public function getSessions(): Collection { return  $this->sessions; }

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

    public function addEvent(Event $event): void
    {
//        if(!$this->events instanceof ArrayCollection) {
//            $this->events = new ArrayCollection();
//        }

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

    public function addCreatedNecessity(Necessity $necessity)
    {
        if(!$this->createdNecessities->contains($necessity)) {
            $this->createdNecessities->add($necessity);
            $necessity->setCreator($this);
        }
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'username' => $this->username,
//            'firstVisit' => $this->firstVisit,
//            'ownedEvents' => $this->getOwnedEvents()->toArray(),
//            'events' => $this->getEvents()->toArray(),
//            'necessities' => $this->getNecessities()->toArray(),
        );
    }
}
