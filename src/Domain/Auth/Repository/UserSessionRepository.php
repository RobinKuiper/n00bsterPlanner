<?php

namespace App\Domain\Auth\Repository;

use App\Application\Base\BaseRepository;
use App\Domain\Auth\Models\UserSession;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final class UserSessionRepository extends BaseRepository
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
        return UserSession::class;
    }

    /**
     * @param array $data
     * @return UserSession
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): UserSession
    {
        $userSession = new UserSession($data['user'], $data['token']);

        $this->save($userSession);

        return $userSession;
    }

    /**
     * @param UserSession $userSession
     * @param array $data
     * @return UserSession
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(UserSession $userSession, array $data = []): UserSession
    {
        $userSession->setLastVisit(new DateTimeImmutable('now'));

        $this->save($userSession);

        return $userSession;
    }
}
