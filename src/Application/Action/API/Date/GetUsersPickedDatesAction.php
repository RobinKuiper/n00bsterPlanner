<?php

namespace App\Application\Action\API\Date;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class GetUsersPickedDatesAction extends DateAction
{
    public function action(): ResponseInterface
    {
        $eventId = $this->args['eventId'];
        $userId = $this->getAttribute('userId');

        $dates = $this->dateService->getUsersPickedDates($eventId, $userId);

        return $this->respond($dates);
    }
}
