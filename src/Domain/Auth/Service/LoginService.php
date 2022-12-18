<?php

namespace App\Domain\Auth\Service;

use App\Application\Factory\ContainerFactory;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use SlimSession\Helper;

final class LoginService
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
            ->addFileHandler('user_login.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function login(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        // Get user from database
        $name = $data['name'];
        $password = $data['password'];
        $user = $this->repository->findOneBy([ 'name' => $name ]);

        // Verify password
        if(!$user || !password_verify($password, $user->getPassword())) {
            // Wrong password, log and return false
            $this->logger->error(sprintf('Sign in failed: %s', $name));

            return [
                'success' => false,
                'errors' => [
                    'Invalid Credentials.'
                ]
            ];
        } else {
            // Correct credentials
            $this->logger->info(sprintf('User logged in successfully: %s', $user->getName()));

            // Set PhpSession
            $container = ContainerFactory::createInstance();
            $session = $container->get(Helper::class);
            $session->set('user', $user);

            // Update user last visit in db
            $this->repository->update($user);

            return [
                'success' => true,
                'user' => $user
            ];
        }
    }
}
