<?php

namespace App\Domain\Event\Repository\EventCategory;

use App\Base\BaseRepository;
use App\Domain\Event\Models\EventCategory\EventCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use DomainException;

final class EventCategoryRepository extends BaseRepository
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @return string
     */
    protected function getModelName(): string
    {
        return EventCategory::class;
    }

    /**
     * @param array $data
     * @return EventCategory
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): EventCategory
    {
        $eventCategory = new EventCategory();
        $eventCategory->setName($data['name']);

        $this->save($eventCategory);

        return $eventCategory;
    }

    /**
     * @param string $eventCategoryName
     * @return EventCategory
     */
    public function getByName(string $eventCategoryName): EventCategory
    {
        $category = $this->entityManager->getRepository($this->getModelName())->findOneBy([ 'name' => $eventCategoryName]);

        if (!$category) {
            throw new DomainException(sprintf('Category not found: %s', $eventCategoryName));
        }

        return $category;
    }
}
