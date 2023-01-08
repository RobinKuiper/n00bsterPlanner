<?php

namespace App\Domain\Auth\Models;

use App\Application\Base\BaseModel;
use App\Domain\Event\Models\Date;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\Necessity;
use App\Domain\Event\Models\PickedDate;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'users')]
class User extends BaseModel
{
    #[Column(type: 'string', unique: true, nullable: true)]
    private string $visitorId;

    #[Column(type: 'string', unique: true, nullable: true)]
    private string $email;

    #[Column(type: 'string', unique: false, nullable: true)]
    private string $password;

    #[Column(name: 'display_name', type: 'string', unique: true, nullable: true)]
    private string $displayName;

    #[Column(type: 'string')]
    private string $color;

    #[Column(name: 'first_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $firstVisit;

    // One User has multiple UserSessions
    /** @var Collection<int, UserSession> */
    #[OneToMany(mappedBy: 'user', targetEntity: UserSession::class, cascade: ['persist'])]
    private Collection $sessions;

    // One User has multiple OwnedEvents
    /** @var Collection<int, Event> */
    #[OneToMany(mappedBy: 'creator', targetEntity: Event::class, cascade: ['persist'])]
    private Collection $ownedEvents;

    // Many Users have multiple Events
    /** @var Collection<int, Event> */
    #[ManyToMany(targetEntity: Event::class, inversedBy: 'members', cascade: ['persist'])]
    #[JoinTable(name: 'users_events')]
    private Collection $events;

    /** @var Collection<int, PickedDate> */
    #[OneToMany(mappedBy: 'user', targetEntity: PickedDate::class, cascade: ['persist'])]
    private Collection $pickedDates;

    // Many Users have multiple necessities
    /** @var Collection<int, Necessity> */
    #[ManyToMany(targetEntity: Necessity::class, inversedBy: 'members', cascade: ['persist'])]
    #[JoinTable(name: 'users_necessities')]
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
        $this->pickedDates = new ArrayCollection();
        $this->ownedEvents = new ArrayCollection();
        $this->necessities = new ArrayCollection();
        $this->createdNecessities = new ArrayCollection();
    }

    public function getVisitorId(): string
    {
        return $this->visitorId;
    }
    public function setVisitorId(string $visitorId): void
    {
        $this->visitorId = $visitorId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getColor(): string
    {
        return $this->color;
    }
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstVisit(): DateTimeImmutable
    {
        return $this->firstVisit;
    }
    public function setFirstVisit(DateTimeImmutable $date): void
    {
        $this->firstVisit = $date;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getOwnedEvents(): Collection
    {
        return $this->ownedEvents;
    }

    /**
     * @param Collection<int, Event> $ownedEvents
     * @return void
     */
    public function setOwnedEvents(Collection $ownedEvents): void
    {
        $this->ownedEvents = $ownedEvents;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }
    /**
     * @param Collection<int, Event> $events
     * @return void
     */
    public function setEvents(Collection $events): void
    {
        $this->events = $events;
    }

    /**
     * @return Collection<int, PickedDate>
     */
    public function getPickedDates(): Collection
    {
        return $this->pickedDates;
    }
    /**
     * @param Collection<int, PickedDate> $pickedDates
     * @return void
     */
    public function setPickedDates(Collection $pickedDates): void
    {
        $this->pickedDates = $pickedDates;
    }

    /**
     * @param PickedDate $pickedDate
     * @return void
     */
    public function addPickedDate(PickedDate $pickedDate): void
    {
        if (!$this->pickedDates->contains($pickedDate)) {
            $this->pickedDates->add($pickedDate);
            $pickedDate->setUser($this);
        }
    }

    public function getNecessities(): Collection
    {
        return $this->necessities;
    }

    public function setNecessities(Collection $necessities): void
    {
        $this->necessities = $necessities;
    }

    public function getCreatedNecessities(): Collection
    {
        return $this->createdNecessities;
    }
    public function setCreatedNecessities(Collection $necessities): void
    {
        $this->createdNecessities = $necessities;
    }

    public function getSessions(): Collection
    {
        return  $this->sessions;
    }

    public function addSession(UserSession $session)
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setUser($this);
        }
    }

    public function getAllEvents(bool $split = true): array|Collection
    {
        if ($split) {
            return [
                'events' => $this->events->toArray(),
                'owned' => $this->ownedEvents->toArray()
            ];
        }

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

        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addMember($this);
        }
    }

    public function removeEvent(Event $event): void
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeMember($this);
        }
    }

    public function addOwnedEvent(Event $event): void
    {
        if (!$this->ownedEvents->contains($event)) {
            $this->ownedEvents->add($event);
            $event->setCreator($this);
        }
    }

    public function removeOwnedEvent(Event $event)
    {
        if ($this->ownedEvents->contains($event)) {
            $this->ownedEvents->removeElement($event);
            // Todo: Remove event?
        }
    }

//    public function addDate(Date $date): void
//    {
////        if(!$this->dates instanceof ArrayCollection) {
////            $this->dates = new ArrayCollection();
////        }
//
//        if (!$this->dates->contains($date)) {
//            $this->dates->add($date);
//            $date->addMember($this);
//        }
//    }
//
//    public function removeDate(Date $date): void
//    {
//        if ($this->dates->contains($date)) {
//            $this->dates->removeElement($date);
//            $date->removeMember($this);
//        }
//    }

    public function addNecessity(Necessity $necessity): void
    {
        if (!$this->necessities->contains($necessity)) {
            $this->necessities->add($necessity);
            $necessity->addMember($this);
        }
    }

    public function removeNecessity(Necessity $necessity): void
    {
        if ($this->necessities->contains($necessity)) {
            $this->necessities->removeElement($necessity);
            $necessity->removeMember($this);
        }
    }

    public function addCreatedNecessity(Necessity $necessity): void
    {
        if (!$this->createdNecessities->contains($necessity)) {
            $this->createdNecessities->add($necessity);
            $necessity->setCreator($this);
        }
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'email' => $this->email ?? null,
            'displayName' => $this->displayName ?? null,
            'color' => $this->color !== "" ? $this->color :  'white',
//            'firstVisit' => $this->firstVisit,
//            'ownedEvents' => $this->getOwnedEvents()->toArray(),
//            'events' => $this->getEvents()->toArray(),
//            'necessities' => $this->getNecessities()->toArray(),
        );
    }
}
