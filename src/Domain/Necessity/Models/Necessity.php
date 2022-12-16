<?php

namespace App\Domain\Necessity\Models;

use App\Base\BaseModel;
use App\Domain\Event\Models\Event;
use App\Domain\Invitee\Models\Invitee;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'necessities')]
class Necessity extends BaseModel implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[Column(type: 'string', unique: false, nullable: false)]
    private string $name;

    #[ManyToOne(targetEntity: Event::class)]
    private Event $event;

    #[ManyToOne(targetEntity: Invitee::class)]
    private Invitee $invitee;

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEvent(): Event { return $this->event; }
    public function setEvent(Event $event): void { $this->event = $event; }

    public function getInvitee(): Invitee { return $this->invitee; }
    public function setInvitee(Invitee $invitee): void { $this->invitee = $invitee; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'visitorId' => $this->name,
            'event' => $this->event,
            'invitee' => $this->invitee
        );
    }
}
