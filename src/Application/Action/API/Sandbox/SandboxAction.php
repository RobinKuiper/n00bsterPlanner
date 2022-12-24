<?php

namespace App\Application\Action\API\Sandbox;

use App\Application\Action\API\Action;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Event\EventService;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;

class SandboxAction extends Action
{
    protected EventService $eventService;
    protected EntityManager $entityManager;

    public function __construct(EventService $eventService, EntityManager $entityManager, LoggerFactory $loggerFactory)
    {
        parent::__construct($loggerFactory);
        $this->eventService = $eventService;
        $this->entityManager = $entityManager;
    }

    public function action(): ResponseInterface
    {
        $user = $this->getAttribute('user');

//        $events = $this->entityManager->getRepository(Event::class)->findAll();
//        $user = $this->entityManager->find(User::class, 2);
        $events = $user->getOwnedEvents()->toArray();
        $sessions = $user->getSessions()->toArray();

//        $event = new Event();
//        $event->setTitle("Bloep1");
//        $event->setDescription("Whoooo!");
//        $event->setEndDate(new \DateTimeImmutable('now'));
//        $event->setStartDate(new \DateTimeImmutable('now'));
//        $event->setOwnedBy($user);
//
//        $this->entityManager->persist($event);
//        $this->entityManager->flush();

        // Build the HTTP response
        // Send the HTTP response
        return $this->respond([
//            'event' => $event,
            'user' => $user,
            'events' => $events,
            'sessions' => $sessions,
        ]);
    }
}
