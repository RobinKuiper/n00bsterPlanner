<?php

namespace App\Application\Action\API\Event;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class JoinEventAction extends EventAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        // Extract the form data from the request body
        $identifier = $this->args['identifier'];

        $user = $this->getAttribute('user');

        // Invoke the Domain with inputs and retain the result
        $update = $this->eventService->joinEvent($identifier, $user);

        // Get the appropriate status code
        $statusCode = $update['success'] ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;

        // Build the HTTP response
        // Send the HTTP response
        return $this->respond($update, $statusCode);
    }
}
