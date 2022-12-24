<?php

namespace App\Domain\Event;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Service\EventValidator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
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
                'event' => $event
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
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
        $event->setDescription($data['description']);
        $event->setStartDate(new DateTimeImmutable($data['startDate']));
        $event->setEndDate(new DateTimeImmutable($data['endDate']));
        $reference = $this->entityManager->getReference(User::class, $data['user']->getId());
        $event->setOwnedBy($reference);
//        $reference->addOwnedEvent($event);

//        $event->setCategory($data['category']);

//        $invitee = new Invitee();
//        $invitee->setName($data['name']);
//
//        $event->addInvitee($invitee);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}