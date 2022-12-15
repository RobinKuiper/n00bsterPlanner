<?php

namespace App\Domain\User\Repository;

use App\Base\BaseRepository;
use App\Domain\User\Models\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final class UserRepository extends BaseRepository
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @return string
     */
    protected function getModelName(): string
    {
        return User::class;
    }

    /**
     * @param array $data
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): User
    {
        $user = new User();
        $user->setVisitorId($data['visitorId']);

        $this->save($user);

        return $user;
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

        $this->save($user);

        return $user;
    }
}
