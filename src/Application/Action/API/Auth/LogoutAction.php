<?php

namespace App\Application\Action\API\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class LogoutAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        $logout = $this->authService->logout();

        $statusCode = $logout['success'] ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_UNAUTHORIZED;

        return $this->respond($logout, $statusCode);
    }
}
