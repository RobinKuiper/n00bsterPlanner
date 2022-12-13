<?php

namespace App\Domain\Event\Service;

use App\Domain\Event\Models\Event;
use App\Domain\Event\Repository\EventRepository;
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
     */
    public function __construct(
        EventRepository $repository,
        EventValidator $validator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('event_creator.log')
            ->createLogger();
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

        // Insert customer and get new customer ID
        $event = $this->repository->insertEvent($data);

        // Logging
        $this->logger->info(sprintf('Event created successfully: %s', $event->getId()));

        return $event;
    }
}
