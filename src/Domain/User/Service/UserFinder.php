<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;

final class UserFinder
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
     * @return array
     */
    public function find(): array
    {
        // Input validation
        // ...

        return $this->repository->getAll();
    }
}
