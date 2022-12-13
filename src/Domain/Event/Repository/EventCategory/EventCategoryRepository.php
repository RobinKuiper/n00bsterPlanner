<?php

namespace App\Domain\Event\Repository\EventCategory;

use App\Domain\Event\Models\EventCategory\EventCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ObjectRepository;
use DomainException;

final class EventCategoryRepository
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @var EntityRepository|ObjectRepository
     */
    private EntityRepository|ObjectRepository $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(EventCategory::class);
    }

    /**
     * @param array $data
     * @return EventCategory
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function insertEventCategory(array $data): EventCategory
    {
        $eventCategory = new EventCategory();
        $eventCategory->setName($data['name']);

        $this->entityManager->persist($eventCategory);
        $this->entityManager->flush();

        return $eventCategory;
    }

    /**
     * @param string $eventCategoryName
     * @return array|object
     * @throws DomainException
     */
    public function getEventCategoryByName(string $eventCategoryName): EventCategory
    {
        $category = $this->repository->findOneBy([ 'name' => $eventCategoryName]);

        if (!$category) {
            throw new DomainException(sprintf('Category not found: %s', $eventCategoryName));
        }

        return $category;
    }

    /**
     * @return array
     */
    public function findEventCategories(): array
    {
        return $this->repository->findAll();
    }

    public function updateEventCategory(int $eventCategoryId, array $eventCategory): void
    {
        // TODO: Create update functionality
    }

    /**
     * @param int $eventCategoryId
     * @return bool
     */
    public function existsEventCategoryId(int $eventCategoryId): bool
    {
        return (bool)$this->repository->count([ 'id' => $eventCategoryId ]) > 0;
    }

    /**
     * @param int $eventCategoryId
     * @return void
     * @throws ORMException
     */
    public function deleteEventCategoryById(int $eventCategoryId): void
    {
        $eventCategory = $this->repository->find($eventCategoryId);
        try{
            $this->entityManager->remove($eventCategory);
        }catch(ORMException $e){
            throw new ORMException($e);
        }
    }
}
