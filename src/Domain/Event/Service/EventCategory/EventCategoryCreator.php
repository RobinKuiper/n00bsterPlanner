<?php

namespace App\Domain\Event\Service\EventCategory;

use App\Domain\Event\Models\EventCategory\EventCategory;
use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use App\Factory\LoggerFactory;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

final class EventCategoryCreator
{
    /**
     * @var EventCategoryRepository
     */
    private EventCategoryRepository $repository;

    /**
     * @var EventCategoryValidator
     */
    private EventCategoryValidator $validator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EventCategoryRepository $repository
     * @param EventCategoryValidator $validator
     * @param LoggerFactory $loggerFactory
     */
    public function __construct(
        EventCategoryRepository $repository,
        EventCategoryValidator $validator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('event_category_creator.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return EventCategory
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createEventCategory(array $data): EventCategory
    {
        // Input validation
        $this->validator->validateEventCategory($data);

        // Insert customer and get new customer ID
        $eventCategory = $this->repository->insertEventCategory($data);

        // Logging
        $this->logger->info(sprintf('Event Category created successfully: %s', $eventCategory->getId()));

        return $eventCategory;
    }
}
