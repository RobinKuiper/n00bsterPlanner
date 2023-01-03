<?php

namespace App\Domain\Auth;

use App\Application\Factory\ContainerFactory;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Auth\Models\UserSession;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
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

        if (!$user || !password_verify($password, $user->getPassword())) {
            $this->logger->error(sprintf('Sign in failed: %s', $username));

            return [
                'success'       => false,
                'error'         => 'Invalid Credentials.',
                'statusCode'    => StatusCodeInterface::STATUS_UNAUTHORIZED
            ];
        }

        $this->logger->info(sprintf('User logged in successfully: %s', $user->getUsername()));

        $jwt = $this->createOrUpdateSession($user);

        return [
            'success' => true,
            'message' => $jwt
        ];
    }

    public function register(array $data): array
    {
        try {
            // Input validation
            $this->validator->validate($data);

            $user = $this->createNewUser($data);

            $this->logger->info(sprintf('User created successfully: %s', $user->getUsername()));

            $jwt = $this->createOrUpdateSession($user);

            return [
                'success' => true,
                'message' => $jwt
            ];
        } catch (OptimisticLockException|ORMException|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            $this->logger->error(sprintf('Error creating user: %s', $e->getMessage()));

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function registerGuest(): array
    {
        // Input validation
//        $this->validator->validate($data); TODO: Validate data

        try {
            $user = $this->createNewGuestUser();

            $this->logger->info(sprintf('Guest User created successfully: %s', $user->getId()));

            $jwt = $this->createOrUpdateSession($user);

            return [
                'success' => true,
                'message' => $jwt
            ];
        } catch (OptimisticLockException|ORMException $e) {
            $this->logger->error(sprintf('Error creating guest user: %s', $e->getMessage()));

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
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
    private function createOrUpdateSession(User $user): string
    {
        $jwt = $this->generateJWT($user);

        $session = $user->getSessions()->findFirst(function (int $key, UserSession $session) use ($jwt): bool {
            return $session->getToken() == $jwt;
        });

        if ($session) {
            $session->setLastVisit(new DateTimeImmutable("now"));
        } else {
            $session = new UserSession();
            $session->setToken($jwt);
            $user->addSession($session);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

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
        $user->setDisplayName($this->randomDisplayName());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createNewGuestUser(): User
    {
        $user = new User();
        $user->setVisitorId(uuid_create());
        $user->setDisplayName($this->randomDisplayName());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function randomDisplayName(): string
    {
        $userCount = $this->entityManager->getRepository(User::class)->count([]);
        return 'User_' . $userCount;
    }

    private function generateJWT(User $user): string
    {
        $payload = [
            'userId' => $user->getId()
        ];
        return JWT::encode($payload, $this->settings['secret'], 'HS256');
    }
}
