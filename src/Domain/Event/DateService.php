<?php

namespace App\Domain\Event;

use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Event\Models\Date;
use App\Domain\Event\Models\Event;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\ResultSetMapping;
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
     * @param Event $event
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createDate(Event $event, array $data): array
    {
        // Input validation
        $this->validator->validate($data);

        // Insert event
        try {
            $date = $this->makeModel($event, $data);

            $this->logger->info(sprintf('Date created successfully: %s', $date->getId()));

            return [
                'success' => true,
                'date' => $date
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    public function getPickedDates(int $eventId, User $user): array
    {
        $query = 'SELECT dates.date FROM events
INNER JOIN dates ON events.id = dates.event_id
INNER JOIN users_dates ON dates.id = users_dates.date_id
WHERE users_dates.user_id = ' . $user->getId() .'
AND events.id = ' . $eventId;

        $stmt = $this->entityManager->getConnection()->query($query);
        $dates = $stmt->fetchAll();

        return [
            'success' => true,
            'dates' => $dates
        ];
    }

    public function pickDate(Event $event, User $user, array $data): array
    {
        // Validate TODO;
        $user = $this->entityManager->find(User::class, $user->getId());

        // Insert event
        try {
            $date = $this->entityManager->getRepository(Date::class)->findOneBy([
                'date' => new DateTimeImmutable($data['date']),
                'event' => $event
            ]);

            if ($date) {
                $date->addMember($user);
                $this->entityManager->persist($date);
                $this->entityManager->flush();
            }

            $this->logger->info(sprintf('Date picked successfully: %s', $date->getId()));

            return [
                'success' => true,
                'date' => $date
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    public function removeDate(User $user, int $dateId): array
    {
        try {
            $date = $this->entityManager->find(Date::class, $dateId);
            $user = $this->entityManager->find(User::class, $user->getId());

            if (!$date || !$date->canEditorRemove($user)) {
                return [
                    'success' => false,
                    'errors' => ["You may not remove this entity"],
                    'statusCode' => StatusCodeInterface::STATUS_UNAUTHORIZED
                ];
            }

            $this->entityManager->remove($date);
            $this->entityManager->flush();

            return [
                'success' => true
            ];
        } catch (OptimisticLockException|ORMException $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
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
