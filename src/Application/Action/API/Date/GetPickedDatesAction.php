<?php

namespace App\Application\Action\API\Date;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class GetPickedDatesAction extends DateAction
{
    public function action(): ResponseInterface
    {
        $eventId = $this->args['eventId'];
        $userId = $this->getAttribute('userId');

        $dates = $this->dateService->getPickedDates($eventId, $userId);

        return $this->respond($dates);
    }
}
