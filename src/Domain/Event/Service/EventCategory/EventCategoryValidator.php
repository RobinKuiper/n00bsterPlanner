<?php

namespace App\Domain\Event\Service\EventCategory;

use App\Domain\Event\Repository\EventCategory\EventCategoryRepository;
use App\Factory\ConstraintFactory;
use DomainException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

final class EventCategoryValidator
{
    /**
     * @var EventCategoryRepository
     */
    private EventCategoryRepository $repository;

    /**
     * @param EventCategoryRepository $repository
     */
    public function __construct(EventCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $customerId
     * @param array $data
     * @return void
     */
    public function validateCustomerUpdate(int $customerId, array $data): void
    {
        if (!$this->repository->existsCustomerId($customerId)) {
            throw new DomainException(sprintf('Customer not found: %s', $customerId));
        }

        $this->validateEventCategory($data);
    }

    /**
     * @param array $data
     * @return void
     */
    public function validateEventCategory(array $data): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $this->createConstraints());

        if ($violations->count()) {
            throw new ValidationFailedException('Please check your input', $violations);
        }
    }

    /**
     * @return Constraint
     */
    private function createConstraints(): Constraint
    {
        $constraint = new ConstraintFactory();

        return $constraint->collection(
            [
                'name' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
            ]
        );
    }
}
