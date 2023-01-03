<?php

namespace App\Application\Action\API\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RegisterAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();

        $value = $this->authService->register($data);

        return $this->respond($value);
    }
}
