<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Psr\Http\Message\ResponseInterface;

final class GetEventAction extends EventAction
{
    public function action(): ResponseInterface
    {
        // Fetch parameters from the request
        $identifier = (string)$this->args['identifier'];

        $user = $this->getAttribute('user');
        $event = $user->getAllEvents()->findFirst(function(int $key, Event $event) use ($identifier) {
            return $event->getIdentifier() === $identifier;
        });
        return $this->respond($event);
    }
}
