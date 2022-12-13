<?php

namespace App\Domain\Event\Models;

use App\Domain\Event\Models\EventCategory\EventCategory;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'events')]
class Event implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue]
    private int|null $id = null;

    #[Column(type: 'string')]
    private string $title;

    #[Column(type: 'string')]
    private string $description;

    #[ManyToOne(targetEntity: EventCategory::class)]
    private EventCategory $category;

    public function getId(): string { return $this->id; }

    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function getDescription(): string { return $this->description; }
    public function setDescription(string $description): void { $this->description = $description; }

    public function getCategory(): EventCategory { return $this->category; }
    public function setCategory(EventCategory $category): void { $this->category = $category; }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'title'=> $this->description,
            'category' => $this->category
        );
    }
}
