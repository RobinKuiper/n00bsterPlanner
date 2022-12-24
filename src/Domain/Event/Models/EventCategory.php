<?php

namespace App\Domain\Event\Models;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'event_categories')]
class EventCategory implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue]
    private int|null $id = null;

    #[Column(type: 'string')]
    private string $name;

    /** @var Collection<int, Event> */
    #[OneToMany(mappedBy: 'category', targetEntity: Event::class)]
    private Collection $events;

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEvents(): Collection { return $this->events; }

    public function setName(string $name): void { $this->name = $name; }
    public function setEvents(Collection $events): void { $this->events = $events; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'events' => $this->events
        );
    }
}
