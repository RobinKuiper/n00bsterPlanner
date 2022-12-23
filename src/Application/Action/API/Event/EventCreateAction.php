<?php

namespace App\Application\Action\API\Event;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class EventCreateAction extends EventAction
{
    public function action(): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$this->getFormData();
        $data['user'] = $this->getAttribute('user');

        // Invoke the Domain with inputs and retain the result
        $event = $this->eventService->createEvent($data);

        // Get the appropriate status code
        $statusCode = $event['success'] ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_UNAUTHORIZED;

        // Build the HTTP response
        // Send the HTTP response
        return $this->respond($event, $statusCode);
    }
}
