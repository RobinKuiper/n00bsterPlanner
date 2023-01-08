<?php

namespace App\Domain\Event\Models;

use App\Application\Base\BaseModel;
use App\Domain\Auth\Models\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'dates')]
class Date extends BaseModel
{
    #[Column(nullable: true)]
    private DateTimeImmutable $date;

    // Many Meetings have one owner
    #[ManyToOne(targetEntity: Event::class, cascade: ['persist'], inversedBy: 'dates')]
    private Event $event;

    /** @var Collection<int, PickedDate> */
    #[OneToMany(mappedBy: 'date', targetEntity: PickedDate::class, cascade: ['persist'])]
    private Collection $pickedDates;

    public function __construct()
    {
        $this->pickedDates = new ArrayCollection();
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
            $pickedDate->setDate($this);
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
//            'members' => $this->members->toArray(),
        );
    }
}
