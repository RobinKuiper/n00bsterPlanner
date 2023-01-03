<?php

namespace App\Domain\Event;

use App\Application\Factory\ConstraintFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

final class DateValidator
{
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
                'date' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 255),
                    ]
                ),
                'eventId' => $constraint->required(
                    [
                        $constraint->notBlank(),
                    ]
                )
                ,
                'userId' => $constraint->required(
                    [
                        $constraint->notBlank(),
                    ]
                )
            ]
        );
    }
}
