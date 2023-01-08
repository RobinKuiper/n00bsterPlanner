<?php

namespace App\Domain\Auth;

use App\Application\Factory\ConstraintFactory;
use App\Domain\Auth\Models\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

final class UserValidator
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @return void
     */
    public function validate(array $data): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $this->createConstraints());

        $repository = $this->entityManager->getRepository(User::class);
        if ($repository->findOneBy([ 'email' => $data['email'] ])) {
            $violation = new ConstraintViolation(
                'Email already exists.',
                null,
                [],
                $data['email'],
                null,
                $data['email']
            );
            $violations->add($violation);
            // throw new DomainException(sprintf('Email exists: %s', $data['email']));
        }

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
                'email' => $constraint->required(
                    [
                        $constraint->email(),
                    ]
                ),
                'displayName' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(3, 25),
                    ]
                ),
                'password' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(8, 30),
                        $constraint->notCompromisedPassword()
                        // TODO: Require Symbol
                    ]
                ),
            ]
        );
    }
}
