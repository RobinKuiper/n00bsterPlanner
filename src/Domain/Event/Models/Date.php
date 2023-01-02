<?php

namespace App\Domain\Event\Models;

use App\Domain\Auth\Models\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;

#[Entity, Table(name: 'dates')]
class Date implements JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[Column(nullable: true)]
    private DateTimeImmutable $date;

    // Many Meetings have one owner
    #[ManyToOne(targetEntity: Event::class, cascade: ['persist'], inversedBy: 'dates')]
    private Event $event;

    // One Meeting has multiple members
    /** @var Collection<int, User> */
    #[ManyToMany(targetEntity: User::class, mappedBy: 'dates', cascade: ['persist'], fetch: "EAGER")]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
        $event->addDate($this);
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

    public function addMember(User $user): void
    {
        if (!$this->members->contains($user)) {
            $this->members->add($user);
            $user->addDate($this);
        }
    }

    public function removeMember(User $user): void
    {
        if ($this->members->contains($user)) {
            $this->members->removeElement($user);
            $user->removeDate($this);
        }
    }

    public function canEditorRemove(User $user): bool
    {
        return $this->event->isOwner($user);
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'date' => $this->date,
//            'event' => $this->event,
            'members' => $this->members->toArray(),
        );
    }
}
