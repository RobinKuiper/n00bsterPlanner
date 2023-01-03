<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class GetEventAction extends EventAction
{
    public function action(): ResponseInterface
    {
        // Fetch parameters from the request
        $identifier = (string)$this->args['identifier'];

        $user = $this->getAttribute('user');
        $event = $user->getAllEvents(false)->findFirst(function (int $key, Event $event) use ($identifier) {
            return $event->getIdentifier() === $identifier;
        });

        if ($event) {
            $data = [
                'success' => true,
                'message' => $event
            ];
        } else {
            $data = [
                'success'    => false,
                'error'      => 'Event not found',
                'statusCode' => StatusCodeInterface::STATUS_NOT_FOUND
            ];
        }

        return $this->respond($data);
    }
}
