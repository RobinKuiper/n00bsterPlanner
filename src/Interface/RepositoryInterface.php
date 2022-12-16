<?php

namespace App\Interface;

interface RepositoryInterface
{
    public function getById(int $id);
    public function getAll(): array;
    public function findBy(mixed $criteria): array;
    public function findOneBy(mixed $criteria);
    public function exists(int $id): bool;
    public function deleteById(int $id): void;
    public function save($object): void;
    public function create(array $data);
}
