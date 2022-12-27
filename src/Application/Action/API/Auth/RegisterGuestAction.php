<?php

namespace App\Application\Action\API\Auth;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RegisterGuestAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$this->getFormData();

        // Invoke the Domain with inputs and retain the result
        $register = $this->authService->registerGuest($data);

        // Get the appropriate status code
        $statusCode = $register['success'] ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_BAD_REQUEST;

        // Send the HTTP response
        return $this->respond($register, $statusCode);
    }
}
