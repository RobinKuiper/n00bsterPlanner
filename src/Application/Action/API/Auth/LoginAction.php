<?php

namespace App\Application\Action\API\Auth;

use App\Application\Factory\ContainerFactory;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use ElephantIO\Client;
use Psr\Http\Message\ResponseInterface;

final class LoginAction extends AuthAction
{
    /**
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();

        $value = $this->authService->login($data);

        return $this->respond($value);
    }
}
