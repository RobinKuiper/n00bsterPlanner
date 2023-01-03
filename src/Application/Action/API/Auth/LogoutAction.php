<?php

namespace App\Application\Action\API\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class LogoutAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        $data = $this->authService->logout();

        return $this->respond($data);
    }
}
