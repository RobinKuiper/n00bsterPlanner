<?php

namespace App\Application\Action\API\Auth;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Fig\Http\Message\StatusCodeInterface;
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
        // Extract the form data from the request body
        $data = (array)$this->getFormData();

        // Invoke the Domain with inputs and retain the result
        $login = $this->authService->login($data);

        // Get the appropriate status code
        $statusCode = $login['success'] ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_UNAUTHORIZED;

        // Send the HTTP response
        return $this->respond($login, $statusCode);
    }
}
