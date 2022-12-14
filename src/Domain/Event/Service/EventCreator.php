<?php

namespace App\Domain\Event\Service;

use App\Domain\Event\Models\Event;
use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use App\Domain\Event\Repository\EventRepository;
use App\Domain\User\Repository\UserRepository;
use App\Factory\LoggerFactory;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

final class EventCreator
{
    /**
     * @var EventRepository
     */
    private EventRepository $repository;

    /**
     * @var EventCategoryRepository
     */
    private EventCategoryRepository $eventCategoryRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var EventValidator
     */
    private EventValidator $validator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EventRepository $repository
     * @param EventValidator $validator
     * @param LoggerFactory $loggerFactory
     * @param EventCategoryRepository $eventCategoryRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        EventRepository $repository,
        EventValidator $validator,
        LoggerFactory $loggerFactory,
        EventCategoryRepository $eventCategoryRepository,
        UserRepository $userRepository
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('event_creator.log')
            ->createLogger();
        $this->eventCategoryRepository = $eventCategoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return Event
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createEvent(array $data): Event
    {
        // Input validation
        $this->validator->validateEvent($data);

        $data['category'] = $this->eventCategoryRepository->getByName($data['category']);
        $data['user'] = $this->userRepository->getById($data['user']);

        // Insert customer and get new customer ID
        $event = $this->repository->create($data);

        // Logging
        $this->logger->info(sprintf('Event created successfully: %s', $event->getId()));

        return $event;
    }
}
