<?php

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Models\User;
use App\Domain\Auth\Repository\UserRepository;
use DI\NotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;

/**
 * Service.
 */
final class UserReader
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return User
     * @throws NotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function get(int $id): User
    {
        // Input validation
        // ...

        // Fetch data from the database
        return $this->repository->getById($id);

        // Optional: Add or invoke your complex business logic here
        // ...

    }
}
