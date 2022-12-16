<?php

namespace App\Domain\Invitee\Models;

use App\Base\BaseModel;
use App\Domain\Event\Models\Event;
use App\Domain\Necessity\Models\Necessity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'invitees')]
class Invitee extends BaseModel implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[Column(type: 'string', unique: false, nullable: false)]
    private string $name;

    #[Column(type: 'string', unique: true, nullable: true)]
    private string $visitorId;

    #[ManyToOne(targetEntity: Event::class)]
    private Event $event;

    /** @var Collection<int, Invitee> */
    #[OneToMany(mappedBy: 'invitee', targetEntity: Necessity::class)]
    private Collection $necessities;

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getVisitorId(): string { return $this->visitorId; }
    public function setVisitorId(string $visitorId): void { $this->visitorId = $visitorId; }

    public function getEvent(): Event { return $this->event; }
    public function setEvent(Event $event): void { $this->event = $event; }

    public function getNecessities(): Collection { return $this->necessities; }
    public function setNecessities(Collection $necessities): void { $this->necessities = $necessities; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'visitorId' => $this->visitorId,
            'events' => $this->event,
            'necessities' => $this->necessities
        );
    }
}
