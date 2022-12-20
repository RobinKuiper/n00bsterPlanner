<?php

namespace App\Domain\Event\Models;

use App\Application\Base\BaseModel;
use App\Domain\Auth\Models\User;
use Doctrine\ORM\Mapping as ORM;
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

    #[ManyToOne(cascade: ["persist"], inversedBy: 'necessities')]
    private Event $event;

    #[ManyToOne(cascade: ["persist"], inversedBy: "events")]
    private User|null $member;

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEvent(): Event { return $this->event; }
    public function setEvent(Event $event): void { $this->event = $event; }

    public function getMember(): User { return $this->member; }
    public function setMember(User|null $member): void { $this->member = $member; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'visitorId' => $this->name,
            'event' => $this->event,
            'member' => $this->member
        );
    }
}
