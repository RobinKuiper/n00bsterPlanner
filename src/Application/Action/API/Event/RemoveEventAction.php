<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RemoveEventAction extends EventAction
{
    public function action(): ResponseInterface
    {
        $eventId = (int)$this->args['event_id'];
        $userId = $this->getAttribute('userId');

        $remove = $this->eventService->removeEvent($eventId, $userId);

        return $this->respond($remove);
    }
}
