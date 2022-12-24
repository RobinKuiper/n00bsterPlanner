<?php

namespace App\Application\Action\API\Event;

use Psr\Http\Message\ResponseInterface;

final class GetAllEventsAction extends EventAction
{
    public function action(): ResponseInterface
    {
        $user = $this->getAttribute('user');
        $events = $user->getAllEvents()->toArray();
        return $this->respond($events);
    }
}
