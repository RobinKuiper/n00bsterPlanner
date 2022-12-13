<?php

namespace App\Domain\Event\Service;

use App\Domain\Event\Repository\EventRepository;

final class EventFinder
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
     * @return array
     */
    public function findEvents(): array
    {
        // Input validation
        // ...

        $events = $this->repository->findEvents();

        return $events;
    }
}
