<?php

namespace App\Domain\Event\Repository;

use App\Application\Base\BaseRepository;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\Invitee;
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
     * @param array $data ['title', 'description', 'startDate', 'endDate', 'category', 'name']
     * @return Event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): Event
    {
        $event = new Event();
        $event->setTitle($data['title']);
        $event->setDescription($data['description']);
        $event->setStartDate(new \DateTimeImmutable($data['startDate']));
        $event->setEndDate(new \DateTimeImmutable($data['endDate']));
//        $event->setCategory($data['category']);

        $invitee = new Invitee();
        $invitee->setName($data['name']);

        $event->addInvitee($invitee);

        $this->save($event);

        return $event;
    }
}
