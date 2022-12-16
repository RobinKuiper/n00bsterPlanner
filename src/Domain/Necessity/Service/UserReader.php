<?php

namespace App\Domain\Necessity\Service;

use App\Domain\User\Models\User;
use App\Domain\User\Repository\UserRepository;

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
     */
    public function get(int $id): User
    {
        // Input validation
        // ...

        // Fetch data from the database
        $user = $this->repository->getById($id);

        // Optional: Add or invoke your complex business logic here
        // ...

        return $user;
    }
}
