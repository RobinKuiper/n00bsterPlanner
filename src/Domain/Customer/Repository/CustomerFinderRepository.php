<?php

namespace App\Domain\Customer\Repository;

use Doctrine\ORM\EntityManager;
use App\Domain\Customer\Models\Customer;

final class CustomerFinderRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findCustomers(): array
    {
        $customers = $this->entityManager->getRepository(Customer::class)->findAll();

        return $customers ?: [];
    }
}
