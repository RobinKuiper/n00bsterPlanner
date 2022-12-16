<?php

namespace App\Domain\Necessity\Service;

use App\Domain\User\Models\User;
use App\Domain\User\Repository\UserRepository;
use App\Factory\LoggerFactory;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

final class UserUpdater
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    /**
     * @var UserValidator
     */
    private UserValidator $validator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param UserRepository $repository
     * @param UserValidator $validator
     * @param LoggerFactory $loggerFactory
     */
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
