<?php

namespace App\Domain\Auth\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'user_sessions')]
class UserSession implements \JsonSerializable
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $token;

    #[Column(name: 'issued_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $issuedAt;

    #[Column(name: 'last_visit', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $lastVisit;

    #[ManyToOne(targetEntity: User::class, cascade: ['persist', 'remove'], inversedBy: 'sessions')]
    private User $user;

    public function __construct(User $user = null, string $token = null)
    {
        $this->issuedAt = new DateTimeImmutable();
        $this->lastVisit = new DateTimeImmutable();
    }

    public function getId(): int { return $this->id; }

    public function getToken(): string { return $this->token; }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getIssuedAt(): DateTimeImmutable { return $this->issuedAt; }

    public function setIssuedAt(DateTimeImmutable $issuedAt): self
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    public function getLastVisit(): DateTimeImmutable { return $this->lastVisit; }

    public function setLastVisit(DateTimeImmutable $lastVisit): self
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    public function getUser(): User { return $this->user; }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'token' => $this->token,
            'issued_at' => $this->issuedAt,
            'last_visit' => $this->lastVisit,
            'user' => $this->user,
        ];
    }
}
