<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RemoveEventAction extends EventAction
{
    public function action(): ResponseInterface
    {
        // Fetch parameters from the request
        $eventId = (int)$this->args['event_id'];

        $user = $this->getAttribute('user');
        $event = $user->getOwnedEvents()->findFirst(function(int $key, Event $event) use ($eventId) {
            return $event->getId() === $eventId;
        });

        $remove = $this->eventService->removeEvent($event);

        $statusCode = $remove['success'] ? StatusCodeInterface::STATUS_OK : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;

        return $this->respond($remove, $statusCode);
    }
}
