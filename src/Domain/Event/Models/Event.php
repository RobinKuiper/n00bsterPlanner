<?php

namespace App\Domain\Event\Models;

use App\Application\Base\BaseModel;
use App\Domain\Auth\Models\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Unique;

#[Entity, Table(name: 'events')]
class Event extends BaseModel
{
    #[Column(type: 'string'), NotNull, Unique]
    private string $identifier;

    #[Column(type: 'string')]
    private string $title;

    #[Column(type: 'string')]
    private string $description;

    // One Meeting has multiple dates
    /** @var Collection<int, Date> */
    #[OneToMany(mappedBy: 'event', targetEntity: Date::class, cascade: ['persist'], fetch: "EAGER")]
    private Collection $dates;

    /** @var Collection<int, PickedDate> */
    #[OneToMany(mappedBy: 'user', targetEntity: PickedDate::class, cascade: ['persist'])]
    private Collection $pickedDates;

//    #[ManyToOne(targetEntity: EventCategory::class, inversedBy: 'events')]
//    private EventCategory $category;

    // Many Meetings have one owner
    #[ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'ownedEvents')]
    private User $creator;

    // One Meeting has multiple members
    /** @var Collection<int, User> */
    #[ManyToMany(targetEntity: User::class, mappedBy: 'events', cascade: ['persist'], fetch: "EAGER")]
    private Collection $members;

    // One Meeting has multiple necessities
    /** @var Collection<int, Necessity> */
    #[OneToMany(mappedBy: 'event', targetEntity: Necessity::class, cascade: ['persist'], fetch: "EAGER")]
    private Collection $necessities;

    public function __construct()
    {
        $this->identifier = uuid_create();
        $this->members = new ArrayCollection();
        $this->necessities = new ArrayCollection();
        $this->dates = new ArrayCollection();
        $this->pickedDates = new ArrayCollection();
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection<int, Date>
     */
    public function getDates(): Collection
    {
        return $this->dates;
    }
    /**
     * @param Collection<int, Date> $dates
     * @return void
     */
    public function setDates(Collection $dates): void
    {
        $this->dates = $dates;
    }

    public function addDate(Date $date): void
    {
//        if(!$this->members instanceof ArrayCollection) {
//            $this->members = new ArrayCollection();
//        }

        if (!$this->dates->contains($date)) {
            $this->dates->add($date);
            $date->setEvent($this);
        }
    }

    public function removeDate(Date $date): void
    {
        if ($this->dates->contains($date)) {
            $this->dates->removeElement($date);
        }
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
            $pickedDate->setEvent($this);
        }
    }

//    public function getCategory(): EventCategory { return $this->category; }
//    public function setCategory(EventCategory $category): void { $this->category = $category; }

    public function getCreator(): User
    {
        return $this->creator;
    }
    public function setCreator(User $user): void
    {
        $this->creator = $user;
        $user->addOwnedEvent($this);
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    /**
     * @param Collection<int, User> $members
     * @return void
     */
    public function setMembers(Collection $members): void
    {
        $this->members = $members;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasMember(User $user): bool
    {
        return $this->isOwner($user) || $this->members->contains($user);
    }

    /**
     * @return Collection<int, Necessity>
     */
    public function getNecessities(): Collection
    {
        return $this->necessities;
    }

    /**
     * @param Collection<int, Necessity> $necessities
     * @return void
     */
    public function setNecessities(Collection $necessities): void
    {
        $this->necessities = $necessities;
    }

    public function addMember(User $user): void
    {
//        if(!$this->members instanceof ArrayCollection) {
//            $this->members = new ArrayCollection();
//        }

        if (!$this->members->contains($user)) {
            $this->members->add($user);
            $user->addEvent($this);
        }
    }

    public function removeMember(User $user): void
    {
        if ($this->members->contains($user)) {
            $this->members->removeElement($user);
            $user->removeEvent($this);
        }
    }

    public function addNecessity(Necessity $necessity): void
    {
        if (!$this->necessities->contains($necessity)) {
            $this->necessities->add($necessity);
            $necessity->setEvent($this);
        }
    }

    public function removeNecessity(Necessity $necessity): void
    {
        if ($this->necessities->contains($necessity)) {
            $this->necessities->removeElement($necessity);
        }
    }

    public function isOwner(User $user = null): bool
    {
        return $this->creator === $user;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'identifier' => $this->identifier,
            'title'=> $this->title,
            'description'=> $this->description,
            'dates' => $this->dates->toArray(),
//            'category' => $this->category,
            'owner' => $this->creator,
            'members' => $this->members->toArray(),
            'necessities' => $this->necessities->toArray()
        );
    }
}
