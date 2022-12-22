<?php

namespace App\Domain\Auth\Service;

use App\Application\Factory\ContainerFactory;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Auth\Models\UserSession;
use App\Domain\Auth\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Firebase\JWT\JWT;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

final class AuthenticationService
{
    private UserRepository $userRepository;
    private UserValidator $validator;
    private LoggerInterface $logger;
    private array $settings;

    /**
     * @param UserRepository $repository
     * @param UserValidator $validator
     * @param LoggerFactory $loggerFactory
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __construct(
        UserRepository $repository,
        UserValidator $validator,
        LoggerFactory $loggerFactory
    ) {
        $this->userRepository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('authentication.log')
            ->createLogger();

        $container = ContainerFactory::createInstance();
        $this->settings = $container->get('settings')['authentication'];
    }

    /**
     * @param array $data
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function login(array $data): array
    {
        // TODO: Input validation

        $username = $data['username'];
        $password = $data['password'];

        $user = $this->userRepository->findOneBy([ 'username' => $username ]);

        if(!$user || !password_verify($password, $user->getPassword())) {
            $this->logger->error(sprintf('Sign in failed: %s', $username));

            return [
                'success' => false,
                'errors' => [
                    'Invalid Credentials.'
                ]
            ];
        }

        $this->logger->info(sprintf('User logged in successfully: %s', $user->getUsername()));

        $jwt = $this->createSession($user);

        return [
            'success' => true,
            'token' => $jwt
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function register(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        $username = $data['username'];
        $password = $data['password'];

        $user = $this->userRepository->create([
            'username' => $username,
            'password' => hash_password($password)
        ]);

        $this->logger->info(sprintf('User created successfully: %s', $user->getUsername()));

        $jwt = $this->createSession($user);

        return [
            'success' => true,
            'jwt' => $jwt
        ];
    }

    public function logout(): array
    {
        return [
            'success' => false
        ];
    }

    /**
     * @param User $user
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createSession(User $user): string
    {
        $payload = [
            'userId' => $user->getId(),
            'username' => $user->getUsername(),
        ];
        $jwt = JWT::encode($payload, $this->settings['secret'], 'HS256');

        $session = new UserSession();
        $session->setToken($jwt);
        $user->addSession($session);
        $this->userRepository->save($user);

//        $this->userSessionRepository->create([
//            'user' => $user,
//            'jwt' => $jwt
//        ]);

        return $jwt;
    }
}
