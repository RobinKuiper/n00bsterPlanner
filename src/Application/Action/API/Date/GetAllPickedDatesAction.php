<?php

namespace App\Application\Action\API\Date;

use Psr\Http\Message\ResponseInterface;

final class GetAllPickedDatesAction extends DateAction
{
    public function action(): ResponseInterface
    {
        $eventId = $this->args['eventId'];
        $userId = $this->getAttribute('userId');

        $dates = $this->dateService->getAllPickedDates($eventId, $userId);

        return $this->respond($dates);
    }
}
