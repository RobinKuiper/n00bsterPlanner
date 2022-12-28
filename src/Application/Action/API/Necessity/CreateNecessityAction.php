<?php

namespace App\Application\Action\API\Necessity;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class CreateNecessityAction extends NecessityAction
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

        $event = $user->getAllEvents()->findFirst(function(int $key, Event $event) use ($data) {
            return $event->getId() == $data['eventId'];
        });

        if(!$event){
            return $this->respond([
                'success' => false,
                'errors' => ["You are not allowed to add a necessity to this event,"]
            ], StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        // Invoke the Domain with inputs and retain the result
        $necessity = $this->necessityService->createNecessity($event, $user, $data);

        // Get the appropriate status code
        $statusCode = $necessity['success'] ? StatusCodeInterface::STATUS_CREATED : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;

        // Build the HTTP response
        // Send the HTTP response
        return $this->respond($necessity, $statusCode);
    }
}
