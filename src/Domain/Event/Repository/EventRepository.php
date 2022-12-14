<?php

namespace App\Domain\Event\Repository;

use App\Base\BaseRepository;
use App\Domain\Event\Models\Event;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final class EventRepository extends BaseRepository
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @return string
     */
    protected function getModelName(): string
    {
        return Event::class;
    }

    /**
     * @param array $data
     * @return Event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): Event
    {
        $event = new Event();
        $event->setTitle($data['title']);
        $event->setDescription($data['description']);
        $event->setCategory($data['category']);
        $event->setUser($data['user']);

        $this->save($event);

        return $event;
    }
}
