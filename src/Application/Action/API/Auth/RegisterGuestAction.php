<?php

namespace App\Application\Action\API\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RegisterGuestAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        $value = $this->authService->registerGuest();

        return $this->respond($value);
    }
}
