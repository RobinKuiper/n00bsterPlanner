<?php

namespace App\Domain\Auth\Service;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Auth\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

final class UserUpdater
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    private UserValidator $validator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(
        UserRepository $repository,
        UserValidator $validator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('user_updater.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(array $data): User
    {
        // Input validation
        $this->validator->validate($data);

        $visitorId = $data['visitorId'];
        $user = $this->repository->findOneBy([ 'visitorId' => $visitorId ]);

        if(!$user) {
            $user = $this->repository->create($data);
        } else {
            $user = $this->repository->update($user, $data);
        }

        // Logging
        $this->logger->info(sprintf('User updated successfully: %s', $user->getId()));

        return $user;
    }
}
