<?php

namespace App\Application\Action\API\Date;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class PickDateAction extends DateAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$this->getFormData();
        $user = $this->getAttribute('user');

        $event = $user->getAllEvents(false)->findFirst(function(int $key, Event $event) use ($data) {
            return $event->getId() == $data['eventId'];
        });

        if (!$event) {
            return $this->respond([
                'success' => false,
                'errors' => ["You are not allowed to pick a date in this event,"]
            ], StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        // Invoke the Domain with inputs and retain the result
        $date = $this->dateService->pickDate($event, $user, $data);

        // Get the appropriate status code
        $statusCode = $date['success']
            ? StatusCodeInterface::STATUS_CREATED
            : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;


        // Build the HTTP response
        // Send the HTTP response
        return $this->respond($date['date'], $statusCode);
    }
}
