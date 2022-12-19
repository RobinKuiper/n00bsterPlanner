<?php

namespace App\Domain\Event\Service;

use App\Application\Support\Auth;
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
        $event = $this->repository->getById($eventId);

        // Optional: Add or invoke your complex business logic here
        // ...

        return $event;
    }

    /**
     * @param string $identifier
     * @return Event
     */
    public function getByIdentifier(string $identifier): Event|null {
        // TODO: Validation

        $events = Auth::user()->getAllEvents()->toArray();

        $filtered = array_filter($events, function($e) use ($identifier){
            return $e->getIdentifier() === $identifier;
        });

        return count($filtered)? $filtered[0] : null;
    }
}
