<?php

namespace App\Domain\Event;

use App\Application\Factory\ConstraintFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

//use Doctrine\ORM\EntityManager;
//use DomainException;

final class EventValidator
{
//    /**
//     * @var EntityManager
//     */
//    private EntityManager $entityManager;
//
//    /**
//     * @param EntityManager $entityManager
//     */
//    public function __construct(EntityManager $entityManager)
//    {
//        $this->entityManager = $entityManager;
//    }

    /**
     * @param array $data
     * @return void
     */
    public function validate(array $data): void
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
                'id' => $constraint->optional(
                    [
                        $constraint->number()
                    ]
                ),
                'title' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
                'description' => $constraint->optional(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
                'startDate' => $constraint->optional(
                    [
                        $constraint->notBlank()
                    ]
                ),
                'endDate' => $constraint->optional(
                    [
                        $constraint->notBlank()
                    ]
                ),
                'category' => $constraint->optional(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
                'user' => $constraint->required(
                    [
                        $constraint->notBlank()
                    ]
                ),
            ]
        );
    }
}
