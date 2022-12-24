<?php

namespace App\Domain\Auth;

use App\Application\Factory\ContainerFactory;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Auth\Models\UserSession;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Firebase\JWT\JWT;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

final class AuthenticationService
{
    private EntityManager $entityManager;
    private UserValidator $validator;
    private LoggerInterface $logger;
    private array $settings;

    /**
     * @param EntityManager $entityManager
     * @param UserValidator $validator
     * @param LoggerFactory $loggerFactory
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __construct(
        EntityManager $entityManager,
        UserValidator $validator,
        LoggerFactory $loggerFactory
    ) {
        $this->entityManager = $entityManager;
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

        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneBy([ 'username' => $username ]);

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

        $user = $this->createNewUser($data);

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
        $this->entityManager->persist($user);
        $this->entityManager->flush();

//        $this->userSessionRepository->create([
//            'user' => $user,
//            'jwt' => $jwt
//        ]);

        return $jwt;
    }

    /**
     * @param array $data
     * @return User
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createNewUser(array $data): User
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword(hash_password($data['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}