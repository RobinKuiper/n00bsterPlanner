<?php

namespace App\Domain\Event\Repository;

use App\Domain\Event\Models\Event;
use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectRepository;
use DomainException;

final class EventRepository
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @var EventCategoryRepository
     */
    private EventCategoryRepository $eventCategoryRepository;

    /**
     * @var EntityRepository|ObjectRepository
     */
    private EntityRepository|ObjectRepository $repository;

    /**
     * @param EntityManager $entityManager
     * @param EventCategoryRepository $eventCategoryRepository
     */
    public function __construct(EntityManager $entityManager, EventCategoryRepository $eventCategoryRepository)
    {
        $this->entityManager = $entityManager;

        $this->eventCategoryRepository = $eventCategoryRepository;

        $this->repository = $this->entityManager->getRepository(Event::class);
    }

    /**
     * @param array $data
     * @return Event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insertEvent(array $data): Event
    {
        $category = $this->eventCategoryRepository->getEventCategoryByName($data['category']);

        $event = new Event();
        $event->setTitle($data['title']);
        $event->setDescription($data['description']);
        $event->setCategory($category);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    /**
     * @param int $eventId
     * @return array
     */
    public function getEventById(int $eventId): Event
    {
        $event = $this->repository->find($eventId);

        if (!$event) {
            throw new DomainException(sprintf('Event not found: %s', $eventId));
        }

        return $event;
    }

    /**
     * @return array
     */
    public function findEvents(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param int $eventId
     * @param array $event
     * @return void
     */
    public function updateEvent(int $eventId, array $event): void
    {
        // TODO: Create update functionality
    }

    /**
     * @param int $eventId
     * @return bool
     */
    public function existsEventId(int $eventId): bool
    {
        return (bool)$this->repository->count([ 'id' => $eventId ]) > 0;
    }

    /**
     * @param int $eventId
     * @return void
     * @throws ORMException
     */
    public function deleteEventById(int $eventId): void
    {
        $event = $this->repository->find($eventId);
        try{
            $this->entityManager->remove($event);
        } catch(ORMException $e) {
            throw new ORMException($e);
        }
    }
}
