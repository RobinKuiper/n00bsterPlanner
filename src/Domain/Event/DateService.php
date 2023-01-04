<?php

namespace App\Domain\Event;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Event\Models\Date;
use App\Domain\Event\Models\Event;
use App\Domain\Event\Models\PickedDate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Log\LoggerInterface;

final class DateService
{
    private EntityManager $entityManager;
    private DateValidator $validator;
    private LoggerInterface $logger;

    public function __construct(
        EntityManager $entityManager,
        DateValidator $validator,
        LoggerFactory $loggerFactory,
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->logger = $loggerFactory
            ->addFileHandler('dates.log')
            ->createLogger();
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createDate(array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        // Insert event
        try {
            $user = $this->entityManager->find(User::class, $data['userId']);
            $event = $this->entityManager->find(Event::class, $data['eventId']);

            if (!$event || !$user || !$event->isOwner($user)) {
                return [
                    'success'    => false,
                    'error'      => "You are not allowed to add a date to this event,",
                    'statusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            }

            $date = $this->makeModel($event, $data);

            $this->logger->info(sprintf('Date created successfully: %s', $date->getId()));

            return [
                'success' => true,
                'message' => $date
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    public function getPickedDates(int $eventId, int $userId): array
    {
        try {
            $event = $this->entityManager->find(Event::class, $eventId);
            $user = $this->entityManager->find(User::class, $userId);
            $pickedDates = $this->entityManager->getRepository(PickedDate::class)->findBy([
                'event' => $event,
                'user' => $user
            ]);

            $dates = [];
            foreach ($pickedDates as $pickedDate) {
                $dates[] = $pickedDate->getDate();
            }

            return [
                'success' => true,
                'message' => $dates
            ];
        } catch (Exception $e) {
            return [
                'success'    => false,
                'error'      => $e->getMessage()
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function pickDate(array $data): array
    {
        // Validate TODO;

        try {
            $user = $this->entityManager->find(User::class, $data['userId']);
            $event = $this->entityManager->find(Event::class, $data['eventId']);

            if (!$event || !$event->getId()) {
                return [
                    'success'    => false,
                    'error'      => "You are not allowed to pick a date in this event,",
                    'statusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            }

            $date = $this->entityManager->getRepository(Date::class)->findOneBy([
                'date' => new DateTimeImmutable($data['date']),
                'event' => $event
            ]);

            if ($date && $user) {
                $pickedDate = new PickedDate();
                $pickedDate->setDate($date);
                $pickedDate->setEvent($event);
                $pickedDate->setUser($user);
                $this->entityManager->persist($pickedDate);
                $this->entityManager->flush();

                $this->logger->info(sprintf('Date picked successfully: %s', $date->getId()));

                return [
                    'success' => true,
                    'message' => $date
                ];
            } else {
                return [
                    'success' => false,
                    'statusCode' => StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR
                ];
            }
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function unpickDate(array $data): array
    {
        // Validate TODO;

        // Insert event
        try {
            $user = $this->entityManager->find(User::class, $data['userId']);
            $event = $this->entityManager->find(Event::class, $data['eventId']);

            $date = $this->entityManager->getRepository(Date::class)->findOneBy([
                'date' => new DateTimeImmutable($data['date']),
                'event' => $event
            ]);

            $pickedDate = $this->entityManager->getRepository(PickedDate::class)->findOneBy([
                'date' => $date,
                'event' => $event,
                'user' => $user
            ]);

            if ($pickedDate) {
                $this->entityManager->remove($pickedDate);
                $this->entityManager->flush();

                $this->logger->info('Date unpicked successfully');

                return [
                    'success' => true
                ];
            } else {
                return [
                    'success' => false,
                ];
            }
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    public function removeDate(int $userId, int $eventId, string $date): array
    {
        try {
            $user = $this->entityManager->find(User::class, $userId);
            $event = $this->entityManager->find(Event::class, $eventId);

            if (!$event || !$user || !$event->isOwner($user)) {
                return [
                    'success'    => false,
                    'error'      => "You are not allowed to remove a date form this event,",
                    'statusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            }

            $date = str_replace('-', '/', $date);

            $date = $this->entityManager->getRepository(Date::class)->findOneBy([
                'event' => $event,
                'date'  => new DateTimeImmutable($date)
            ]);

            if (!$date) {
                return [
                    'success' => false,
                    'error'   => 'Date not found.'
                ];
            }

            $this->entityManager->remove($date);
            $this->entityManager->flush();

            $this->logger->info('Date remove successfully');

            return [
                'success' => true
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'error'   => $e->getMessage()
            ];
        }
    }

    /**
     * @param Event $event
     * @param array $data
     * @return Date
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function makeModel(Event $event, array $data): Date
    {
        $date = new Date();
        $date->setDate(new DateTimeImmutable($data['date']));

        $eventR = $this->entityManager->getReference(Event::class, $event->getId());
        /** @var Event $eventR */
        $date->setEvent($eventR);
        $eventR->addDate($date);

        $this->entityManager->persist($eventR);
        $this->entityManager->flush();

        return $date;
    }
}
