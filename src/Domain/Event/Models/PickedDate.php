<?php

namespace App\Domain\Event\Models;

use App\Application\Base\BaseModel;
use App\Domain\Auth\Models\User;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'picked_dates')]
class PickedDate extends BaseModel
{
    #[ManyToOne(targetEntity: Date::class, inversedBy: 'pickedDates')]
    private Date $date;

    #[ManyToOne(targetEntity: Event::class, inversedBy: 'pickedDates')]
    private Event $event;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'pickedDates')]
    private User $user;

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
        $event->addPickedDate($this);
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function setDate(Date $date): void
    {
        $this->date = $date;
        $date->addPickedDate($this);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $user->addPickedDate($this);
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'date' => $this->date,
            'user' => $this->user
        );
    }
}
