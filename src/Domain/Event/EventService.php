<?php

namespace App\Domain\Event;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Event\Models\Event;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Log\LoggerInterface;

final class EventService
{
    private EntityManager $entityManager;
//    private ObjectRepository $repository;
    private EventValidator $validator;
    private LoggerInterface $logger;

    public function __construct(
        EntityManager $entityManager,
        EventValidator $validator,
        LoggerFactory $loggerFactory,
    ) {
        $this->entityManager = $entityManager;
//        $this->repository = $entityManager->getRepository(Event::class);
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('events.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createEvent(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        // Insert event
        try {
            $event = $this->makeModel($data);

            $this->logger->info(sprintf('Event created successfully: %s', $event->getId()));

            return [
                'success' => true,
                'message' => $event
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    /**
     * @param Event $event
     * @param array $data
     * @return array
     */
    public function updateEvent(array $data): array
    {
        // Input validation
//        $this->validator->validate($data); TODO: Validate data

        try {
            $event = $this->entityManager->find(Event::class, $data['eventId']);
            $user = $this->entityManager->find(User::class, $data['userId']);

            if (!$event || !$user || !$event->isOwner($user)) {
                return [
                    'success'       => false,
                    'error'         => 'You are not allowed to update this event',
                    'statusCode'    => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            } else {
                $event->setDescription($data['description'] ?? $event->getDescription());
                $event->setTitle($data['title'] ?? $event->getTitle());

                $this->entityManager->persist($event);
                $this->entityManager->flush();

                return [
                    'success' => true,
                    'message' => $event
                ];
            }
        } catch (ORMException|Exception $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    public function removeEvent(int $eventId, int $userId): array
    {
        try {
            $event = $this->entityManager->find(Event::class, $eventId);
            $user = $this->entityManager->find(User::class, $userId);

            if ($event && $event->isOwner($user)) {
                $this->entityManager->remove($event);
                $this->entityManager->flush();

                return [
                    'success' => true
                ];
            } else {
                return [
                    'success'    => false,
                    'statusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED,
                    'error'      => 'Not allowed'
                ];
            }
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success'   => false,
                'error'     => $e->getMessage()
            ];
        }
    }

    public function joinEvent(string $identifier, int $userId): array
    {
        try {
            $repository = $this->entityManager->getRepository(Event::class);
            $event = $repository->findOneBy([ 'identifier' => $identifier ]);
            $user = $this->entityManager->find(User::class, $userId);

            if (!$event || !$user || $event->hasMember($user)) {
                return [
                    'success'       => false,
                    'error'         => "You are not allowed to join this event.",
                    'statusCode'    => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            }

            $event->addMember($user);

            $this->entityManager->persist($event);
            $this->entityManager->flush();

            return [
                'success' => true,
                'message' => $event
            ];
        } catch (ORMException $e) {
            return [
                'success'       => false,
                'error'         => $e->getMessage(),
                'statusCode'    => StatusCodeInterface::STATUS_BAD_REQUEST
            ];
        }
    }

    /**
     * @param array $data
     * @return Event
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function makeModel(array $data): Event
    {
        $event = new Event();
        $event->setTitle($data['title']);
        $event->setDescription($data['description'] ?? "");
        $reference = $this->entityManager->getReference(User::class, $data['user']->getId());
        $event->setCreator($reference);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}
