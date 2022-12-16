<?php

namespace App\Domain\Event\Models;

use App\Domain\Event\Models\EventCategory\EventCategory;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
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

    #[Column(name: 'start_date', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $startDate;

    #[Column(name: 'end_date', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $endDate;

    #[ManyToOne(targetEntity: EventCategory::class)]
    private EventCategory $category;

    /** @var Collection<int, Invitee> */
    #[OneToMany(mappedBy: 'event', targetEntity: Invitee::class)]
    private Collection $invitees;

    /** @var Collection<int, Necessity> */
    #[OneToMany(mappedBy: 'event', targetEntity: Necessity::class)]
    private Collection $necessities;

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

    public function getStartDate(): DateTimeImmutable { return $this->startDate; }
    public function setStartDate(DateTimeImmutable $date): void { $this->startDate = $date; }

    public function getEndDate(): DateTimeImmutable { return $this->endDate; }
    public function setEndDate(DateTimeImmutable $date): void { $this->endDate = $date; }

    public function getCategory(): EventCategory { return $this->category; }
    public function setCategory(EventCategory $category): void { $this->category = $category; }

    public function getInvitees(): Collection { return $this->invitees; }
    public function setInvitees(Collection $invitees): void { $this->invitees = $invitees; }

    public function getNecessities(): Collection { return $this->necessities; }
    public function setNecessities(Collection $necessities): void { $this->necessities = $necessities; }

    public function addInvitee(Invitee $invitee): void
    {
        if(!$this->invitees->contains($invitee)){
            $this->invitees[] = $invitee;
            $invitee->setEvent($this);
        }
    }

    public function removeInvitee(Invitee $invitee): void
    {
        if($this->invitees->contains($invitee)){
            $this->invitees->removeElement($invitee);
        }
    }

    public function addNecessity(Necessity $necessity): void
    {
        if(!$this->necessities->contains($necessity)){
            $this->necessities[] = $necessity;
            $necessity->setEvent($this);
        }
    }

    public function removeNecessity(Necessity $necessity): void
    {
        if($this->necessities->contains($necessity)){
            $this->necessities->removeElement($necessity);
        }
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
            'invitees' => $this->invitees,
            'necessities' => $this->necessities
        );
    }
}
