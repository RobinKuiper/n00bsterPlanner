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
                'title' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
                'description' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
                'startDate' => $constraint->required(
                    [
                        $constraint->notBlank()
                    ]
                ),
                'endDate' => $constraint->required(
                    [
                        $constraint->notBlank()
                    ]
                ),
                'category' => $constraint->required(
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
