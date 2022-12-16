<?php

namespace App\Domain\Invitee\Repository;

use App\Base\BaseRepository;
use App\Domain\Invitee\Models\Invitee;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

final class InviteeRepository extends BaseRepository
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
        return Invitee::class;
    }

    /**
     * @param array $data
     * @return Invitee
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): Invitee
    {
        $object = new Invitee;
        $object->setVisitorId($data['visitorId']);
        $object->setEvent([$data['event']]);

        $this->save($object);

        return $object;
    }

    /**
     * @param Invitee $invitee
     * @param array $data
     * @return Invitee
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Invitee $invitee, array $data): Invitee
    {
        $invitee->setEvent($data['events']);

        $this->save($invitee);

        return $invitee;
    }
}
