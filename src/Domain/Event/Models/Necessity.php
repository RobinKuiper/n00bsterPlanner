<?php

namespace App\Domain\Event\Models;

use App\Application\Base\BaseModel;
use App\Domain\Auth\Models\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'necessities')]
class Necessity extends BaseModel
{
    #[Column(type: 'string', unique: false, nullable: false)]
    private string $name;

    #[Column(type: 'integer', unique: false, nullable: false)]
    private int $amount = 1;

    #[ManyToOne(targetEntity: Event::class, cascade: ["persist"], inversedBy: 'necessities')]
    private Event $event;

    #[ManyToOne(cascade: ["persist"], inversedBy: "necessities")]
    private User|null $member;

    #[ManyToOne(cascade: ["persist"], fetch: "EAGER", inversedBy: "createdNecessities")]
    private User $creator;

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getMember(): User
    {
        return $this->member;
    }
    public function setMember(User|null $member): void
    {
        $this->member = $member;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    public function canEditorRemove(User $user): bool
    {
        $isCreator = $this->creator === $user;
        $isEventOwner = $this->event->isOwner($user);
        return $isCreator || $isEventOwner;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'creator' => $this->creator ?? null,
//            'event' => $this->getEvent(),
//            'member' => $this->getMember()
        );
    }
}
