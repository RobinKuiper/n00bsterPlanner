<?php

namespace App\Application\Action\API\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RegisterGuestAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        // Invoke the Domain with inputs and retain the result
        $register = $this->authService->registerGuest();

        // Get the appropriate status code
        $statusCode = $register['success'] ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_BAD_REQUEST;

        // Send the HTTP response
        return $this->respond($register, $statusCode);
    }
}
