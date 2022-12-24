<?php

namespace App\Domain\Event\Service;

use App\Application\Factory\LoggerFactory;
use App\Domain\Event\Models\Necessity;
use App\Domain\Event\NecessityValidator;
use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use App\Domain\Event\Repository\EventRepository;
use DI\NotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

final class NecessityAdder
{
    /**
     * @var EventRepository
     */
    private EventRepository $repository;

    /**
     * @var NecessityValidator
     */
    private NecessityValidator $validator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EventRepository $repository
     * @param NecessityValidator $validator
     * @param LoggerFactory $loggerFactory
     */
    public function __construct(
        EventRepository $repository,
        NecessityValidator $validator,
        LoggerFactory $loggerFactory,
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('necessity_adder.log')
            ->createLogger();
    }


    /**
     * @param array $data ['name']
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function add(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        try {
            $event = $this->repository->getById($data['eventId']);

            $necessity = new Necessity();
            $necessity->setName($data['name']);
            $event->addNecessity($necessity);
            $this->repository->save($event);

            return [
                'success' => true,
                'event' => $event,
                'necessity' => $necessity
            ];
        } catch (NotFoundException|OptimisticLockException|TransactionRequiredException|ORMException $e) {
            return [
                'success' => false,
                'errors' => $e
            ];
        }
    }
}
