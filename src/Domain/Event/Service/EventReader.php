<?php

namespace App\Domain\Event\Service;

use App\Domain\Event\Models\Event;
use App\Domain\Event\Repository\EventRepository;

/**
 * Service.
 */
final class EventReader
{
    /**
     * @var EventRepository
     */
    private EventRepository $repository;

    /**
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $eventId
     * @return Event
     */
    public function getEvent(int $eventId): Event
    {
        // Input validation
        // ...

        // Fetch data from the database
        $event = $this->repository->getEventById($eventId);

        // Optional: Add or invoke your complex business logic here
        // ...

        return $event;
    }
}
