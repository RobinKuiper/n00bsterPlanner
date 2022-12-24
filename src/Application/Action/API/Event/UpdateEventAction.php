<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class UpdateEventAction extends EventAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$this->getFormData();
        $data['user'] = $this->getAttribute('user');

        $user = $this->getAttribute('user');
        $event = $user->getOwnedEvents()->findFirst(function(int $key, Event $event) use ($data) {
            return $event->getId() == $data['id'];
        });

        // Invoke the Domain with inputs and retain the result
        $update = $this->eventService->updateEvent($event, $data);

        // Get the appropriate status code
        $statusCode = $update['success'] ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;

        // Build the HTTP response
        // Send the HTTP response
        return $this->respond($update, $statusCode);
    }
}
