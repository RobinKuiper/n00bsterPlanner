<?php

namespace App\Domain\Auth\Service;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Auth\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

final class RegisterService
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
            ->addFileHandler('user_register.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        $name = $data['name'];
        $user = $this->repository->findOneBy([ 'name' => $name ]);

        if(!$user) {
            $data['password'] = hash_password($data['password']);

            $user = $this->repository->create($data);

            $this->logger->info(sprintf('User created successfully: %s', $user->getId()));

            return [
                'success' => true,
                'user' => $user
            ];
        } else {
            $this->logger->error(sprintf('User name already exists: %s', $name));

            return [
                'success' => false,
                'errors' => [
                    'Name already exists.'
                ]
            ];
        }
    }
}
