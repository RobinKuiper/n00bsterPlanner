<?php

namespace App\Domain\Event\Service;

use App\Application\Factory\LoggerFactory;
use App\Application\Support\Auth;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use App\Domain\Event\Repository\EventRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

final class EventService
{
    private EventRepository $repository;
    private EventValidator $validator;
    private LoggerInterface $logger;

    public function __construct(
        EventRepository $repository,
        EventValidator $validator,
        LoggerFactory $loggerFactory,
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('events.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createEvent(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        // Insert event
        try {
            $event = $this->repository->create($data);

            $this->logger->info(sprintf('Event created successfully: %s', $event->getId()));

            return [
                'success' => true,
                'event' => $event
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }
}
