<?php

namespace App\Domain\Event\Service;

use App\Application\Factory\LoggerFactory;
use App\Application\Support\Auth;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use App\Domain\Event\Repository\EventRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
     */
    public function __construct(
        EventRepository $repository,
        EventValidator $validator,
        LoggerFactory $loggerFactory,
        EventCategoryRepository $eventCategoryRepository,
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('event_creator.log')
            ->createLogger();
        $this->eventCategoryRepository = $eventCategoryRepository;
    }


    /**
     * @param array $data ['title', 'description', 'startDate', 'endDate', 'category', 'name']
     * @return Event
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createEvent(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        $data['category'] = $this->eventCategoryRepository->findOneBy([ 'name' => $data['category'] ]);
        $data['user'] = Auth::user();

        // Insert customer and get new customer ID
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
                'errors' => $e
            ];
        }
    }
}
