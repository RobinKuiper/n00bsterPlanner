<?php

namespace App\Application\Action\API\Event;

use Psr\Http\Message\ResponseInterface;

final class GetOwnedEventsAction extends EventAction
{
    public function action(): ResponseInterface
    {
        $user = $this->getAttribute('user');
        $events = $user->getOwnedEvents()->toArray();
        return $this->respond($events);
    }
}
