<?php

namespace App\Domain\Event\Models;

use App\Domain\Event\Models\EventCategory\EventCategory;
use App\Domain\User\Models\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Unique;

#[Entity, Table(name: 'events')]
class Event implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[Column(type: 'string'), NotNull, Unique]
    private string $identifier;

    #[Column(type: 'string')]
    private string $title;

    #[Column(type: 'string')]
    private string $description;

    #[ManyToOne(targetEntity: EventCategory::class)]
    private EventCategory $category;

    #[ManyToOne(targetEntity: User::class)]
    private User $owner;

    /** @var Collection<int, User> */
    #[ManyToMany(targetEntity: User::class, mappedBy: 'events')]
    private Collection $users;

    public function __construct(){
        $this->identifier = uuid_create();
    }

    public function getId(): string { return $this->id; }

    public function getIdentifier(): string { return $this->identifier; }
    public function setIdentifier(string $identifier): void { $this->identifier = $identifier; }

    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }

    public function getCategory(): EventCategory { return $this->category; }
    public function setCategory(EventCategory $category): void { $this->category = $category; }

    public function getOwner(): User { return $this->owner; }
    public function setOwner(User $owner): void { $this->owner = $owner; }

    public function getUsers(): Collection { return $this->users; }
    public function setUsers(Collection $users): void { $this->users = $users; }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'identifier' => $this->identifier,
            'title'=> $this->description,
            'category' => $this->category,
            'owner' => $this->owner,
            'users' => $this->users
        );
    }
}
