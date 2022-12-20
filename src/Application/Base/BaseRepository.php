<?php

namespace App\Application\Base;

use App\Application\Interface\RepositoryInterface;
use DI\NotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Container\ContainerInterface;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * @return mixed
     */
    abstract protected function getModelName(): string;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @throws ORMException
     */
    public function getReference(int $id)
    {
        return $this->entityManager->getReference($this->getModelName(), $id);
    }

    /**
     * @param int $id
     * @return \#M#C\App\Base\BaseRepository.getModelName|mixed|object
     * @throws NotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function getById(int $id)
    {
        $object = $this->entityManager->find($this->getModelName(), $id);

        if ($object === null) {
            throw new NotFoundException($this->getModelName() . ' with ID ' . $id . ' could not be found.');
        }

        return $object;
    }

    /**
     * @return array|object[]
     */
    public function getAll(): array
    {
        return $this->entityManager->getRepository($this->getModelName())->findAll();
    }

    /**
     * @param mixed $criteria
     * @return array
     */
    public function findBy(mixed $criteria): array
    {
        return $this->entityManager->getRepository($this->getModelName())->findBy($criteria);
    }

    /**
     * @param mixed $criteria
     * @return mixed|object|null
     */
    public function findOneBy(mixed $criteria)
    {
        return $this->entityManager->getRepository($this->getModelName())->findOneBy($criteria);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return (bool)$this->entityManager->getRepository($this->getModelName())->count([ 'id' => $id ]) > 0;
    }

    /**
     * @param int $id
     * @return void
     * @throws NotFoundException
     * @throws ORMException
     */
    public function deleteById(int $id): void
    {
        $object = $this->getById($id);
        $this->entityManager->remove($object);
    }

    /**
     * @param object $object
     * @return void
     * @throws ORMException
     */
    public function persist(object $object): void
    {
        $this->entityManager->persist($object);
    }

    /**
     * @param object $object
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save($object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
