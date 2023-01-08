<?php

namespace App\Application\Action\API\Auth;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Http\Message\ResponseInterface;

final class AddCredentialsAction extends AuthAction
{
    /**
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function action(): ResponseInterface
    {
        $data = $this->getFormData();
        $userId = $this->getAttribute('userId');

        $value = $this->authService->addCredentials($userId, $data);

        return $this->respond($value);
    }
}
