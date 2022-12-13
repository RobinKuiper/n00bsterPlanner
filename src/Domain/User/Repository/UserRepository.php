<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Models\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectRepository;
use DomainException;

final class UserRepository
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @var EntityRepository|ObjectRepository
     */
    private EntityRepository|ObjectRepository $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @param array $data
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insert(array $data): User
    {
        $user = new User();
        $user->setIdentifier($data['identifier']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        $user = $this->repository->find($id);

        if (!$user) {
            throw new DomainException(sprintf('User not found: %s', $id));
        }

        return $user;
    }

    /**
     * @param string $identifier
     * @return User
     */
    public function getByIdentifier(string $identifier): User
    {
        $user = $this->repository->findOneBy([ 'identifier' => $identifier ]);

        if (!$user) {
            throw new DomainException(sprintf('User not found: %s', $identifier));
        }

        return $user;
    }

    /**
     * @return array
     */
    public function find(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(User $user, array $data): User
    {
        $user->setLastVisit(new DateTimeImmutable('now'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return (bool)$this->repository->count([ 'id' => $id ]) > 0;
    }

    /**
     * @param string $identifier
     * @return bool
     */
    public function existsByIdentifier(string $identifier): bool
    {
        return (bool)count($this->repository->findBy([ 'identifier' => $identifier ])) > 0;
    }

    /**
     * @param int $id
     * @return void
     * @throws ORMException
     */
    public function deleteById(int $id): void
    {
        $user = $this->repository->find($id);
        try{
            $this->entityManager->remove($user);
        } catch(ORMException $e) {
            throw new ORMException($e);
        }
    }
}
