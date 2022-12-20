<?php

namespace App\Domain\Event\Models;

use App\Application\Support\Auth;
use App\Domain\Event\Models\EventCategory\EventCategory;
use App\Domain\Auth\Models\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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

    #[Column(name: 'start_date', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $startDate;

    #[Column(name: 'end_date', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $endDate;

    #[ManyToOne(targetEntity: EventCategory::class, inversedBy: 'events')]
    private EventCategory $category;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'ownedEvents')]
    private User $ownedBy;

    /** @var Collection<int, User> */
    #[OneToMany(mappedBy: 'events', targetEntity: User::class)]
    private Collection $members;

    /** @var Collection<int, Necessity> */
    #[OneToMany(mappedBy: 'event', targetEntity: Necessity::class, cascade: ['persist', 'remove'])]
    private Collection $necessities;

    public function __construct(){
        $this->identifier = uuid_create();
        $this->members = new ArrayCollection();
        $this->necessities = new ArrayCollection();
    }

    public function getId(): string { return $this->id; }

    public function getIdentifier(): string { return $this->identifier; }
    public function setIdentifier(string $identifier): void { $this->identifier = $identifier; }

    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }

    public function getStartDate(): DateTimeImmutable { return $this->startDate; }
    public function setStartDate(DateTimeImmutable $date): void { $this->startDate = $date; }

    public function getEndDate(): DateTimeImmutable { return $this->endDate; }
    public function setEndDate(DateTimeImmutable $date): void { $this->endDate = $date; }

    public function getCategory(): EventCategory { return $this->category; }
    public function setCategory(EventCategory $category): void { $this->category = $category; }

    public function getOwnedBy(): User { return $this->ownedBy; }
    public function setOwnedBy(User $user): void { $this->ownedBy = $user; }

    public function getMembers(): Collection { return $this->members; }
    public function setMembers(Collection $members): void { $this->members = $members; }

    public function getNecessities(): Collection { return $this->necessities; }
    public function setNecessities(Collection $necessities): void { $this->necessities = $necessities; }

    public function addMember(User $user): void
    {
        if(!$this->members->contains($user)){
            $this->members->add($user);
            $user->addEvent($this);
        }
    }

    public function removeMember(User $user): void
    {
        if($this->members->contains($user)){
            $this->members->removeElement($user);
            $user->removeEvent($this);
        }
    }

    public function addNecessity(Necessity $necessity): void
    {
        if(!$this->necessities->contains($necessity)){
            $this->necessities->add($necessity);
            $necessity->setEvent($this);
        }
    }

    public function removeNecessity(Necessity $necessity): void
    {
        if($this->necessities->contains($necessity)){
            $this->necessities->removeElement($necessity);
        }
    }

    /**
     * @param User|null $user
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function isOwner(User $user = null): bool
    {
        if($user === null) {
            return $this->ownedBy === Auth::user();
        }

        return $this->ownedBy === $user;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'identifier' => $this->identifier,
            'title'=> $this->title,
            'description'=> $this->description,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'category' => $this->category,
            'ownedBy' => $this->ownedBy,
            'members' => $this->members,
            'necessities' => $this->necessities
        );
    }
}
