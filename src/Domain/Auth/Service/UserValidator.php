<?php

namespace App\Domain\Auth\Service;

use App\Application\Factory\ConstraintFactory;
use App\Domain\Auth\Repository\UserRepository;
use DomainException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

final class UserValidator
{
    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validateCustomerUpdate(int $customerId, array $data): void
    {
        if (!$this->repository->exists($customerId)) {
            throw new DomainException(sprintf('Customer not found: %s', $customerId));
        }

        $this->validate($data);
    }

    /**
     * @param array $data
     * @return void
     */
    public function validate(array $data): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $this->createConstraints());

        if ($this->repository->findOneBy([ 'username' => $data['username'] ])) {
            $violation = new ConstraintViolation('Username already exists.', null, [], $data['username'], null, $data['username']);
            $violations->add($violation);
            // throw new DomainException(sprintf('Username exists: %s', $data['username']));
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
                'username' => $constraint->required(
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
