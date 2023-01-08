<?php

namespace App\Application\Action\API\Auth;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Http\Message\ResponseInterface;

final class GetUserAction extends AuthAction
{
    /**
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function action(): ResponseInterface
    {
        $userId = $this->getAttribute('userId');

        $value = $this->authService->getUser($userId);

        return $this->respond($value);
    }
}
