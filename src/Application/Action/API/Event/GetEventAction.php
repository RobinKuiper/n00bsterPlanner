<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Psr\Http\Message\ResponseInterface;

final class GetEventAction extends EventAction
{
    public function action(): ResponseInterface
    {
        // Fetch parameters from the request
        $eventId = (int)$this->args['event_id'];

        $user = $this->getAttribute('user');
        $event = $user->getAllEvents()->findFirst(function(int $key, Event $event) use ($eventId) {
            return $event->getId() === $eventId;
        });
        return $this->respond($event);
    }
}
