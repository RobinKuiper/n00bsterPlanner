<?php

namespace App\Domain\Necessity;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Event\Models\Event;
use App\Domain\Necessity\Models\Necessity;
use Cassandra\Exception\UnauthorizedException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpUnauthorizedException;

final class NecessityService
{
    private EntityManager $entityManager;
//    private ObjectRepository $repository;
    private NecessityValidator $validator;
    private LoggerInterface $logger;

    public function __construct(
        EntityManager $entityManager,
        NecessityValidator $validator,
        LoggerFactory $loggerFactory,
    ) {
        $this->entityManager = $entityManager;
//        $this->repository = $entityManager->getRepository(Event::class);
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('necessities.log')
            ->createLogger();
    }

    /**
     * @param Event $event
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createNecessity(Event $event, User $user, array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        // Insert event
        try {
            $necessity = $this->makeModel($event, $user, $data);

            $this->logger->info(sprintf('Necessity created successfully: %s', $necessity->getId()));

            return [
                'success' => true,
                'necessity' => $necessity
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * @param Event $event
     * @param array $data
     * @return array
     */
    public function updateEvent(Event $event, array $data): array
    {
        // Input validation
//        $this->validator->validate($data); TODO: Validate data

        try{
            $event = $this->entityManager->getReference(Event::class, $event->getId());

            $event->setDescription($data['description'] ?? $event->getDescription());
            $event->setTitle($data['title'] ?? $event->getTitle());
            $event->setStartDate(new DateTimeImmutable($data['startDate']) ?? $event->getStartDate());
            $event->setEndDate(new DateTimeImmutable($data['endDate']) ?? $event->getEndDate());

            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return [
                'success' => true,
                'event' => $event
            ];
        } catch (ORMException|Exception $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    public function removeNecessity(User $user, int $necessityId): array
    {
        try{
            $necessity = $this->entityManager->find(Necessity::class, $necessityId);
            $user = $this->entityManager->find(User::class, $user->getId());

            if(!$necessity || !$necessity->canEditorRemove($user)){
                return [
                    'success' => false,
                    'errors' => ["You may not remove this entity"],
                    'statusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            }

            $this->entityManager->remove($necessity);
            $this->entityManager->flush();

            return [
                'success' => true
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * @param Event $event
     * @param User $user
     * @param array $data
     * @return Necessity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function makeModel(Event $event, User $user, array $data): Necessity
    {
        $necessity = new Necessity();
        $necessity->setName($data['name']);
        $necessity->setAmount($data['amount'] ?? 1);

        $userR = $this->entityManager->getReference(User::class, $user->getId());
        /** @var User $userR */
        $necessity->setCreator($userR);
        $userR->addCreatedNecessity($necessity);

        $eventR = $this->entityManager->getReference(Event::class, $event->getId());
        /** @var Event $eventR */
        $necessity->setEvent($eventR);
        $eventR->addNecessity($necessity);

        $this->entityManager->persist($eventR);
        $this->entityManager->flush();

        return $necessity;
    }
}