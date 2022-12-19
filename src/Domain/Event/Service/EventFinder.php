<?php

namespace App\Domain\Event\Service;

use App\Application\Support\Auth;
use App\Domain\Event\Repository\EventRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function findEvents(): array
    {
        return $this->repository->findBy([ 'ownedBy' => Auth::user() ]);
    }
}
