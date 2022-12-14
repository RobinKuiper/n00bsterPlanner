<?php

namespace App\Domain\User\Repository;

use App\Base\BaseRepository;
use App\Domain\User\Models\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use DomainException;

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
     * @param string $visitorId
     * @return User
     */
    public function getByVisitorID(string $visitorId): User
    {
        $user = $this->entityManager->getRepository($this->getModelName())->findOneBy([ 'visitorId' => $visitorId ]);

        if (!$user) {
            throw new DomainException(sprintf('User not found: %s', $visitorId));
        }

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

    /**
     * @param string $visitorId
     * @return bool
     */
    public function existsByVisitorId(string $visitorId): bool
    {
        return (bool)count($this->entityManager->getRepository($this->getModelName())->findBy([ 'visitorId' => $visitorId ])) > 0;
    }
}
