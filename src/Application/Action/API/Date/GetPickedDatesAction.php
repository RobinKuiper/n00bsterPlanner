<?php

namespace App\Application\Action\API\Date;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class GetPickedDatesAction extends DateAction
{
    public function action(): ResponseInterface
    {
        $eventId = $this->args['eventId'];
        $user = $this->getAttribute('user');

        $dates = $this->dateService->getPickedDates($eventId, $user);

        // Get the appropriate status code
        $statusCode = $dates['statusCode'] ?? ($dates['success']
            ? StatusCodeInterface::STATUS_OK
            : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);

        return $this->respond($dates['dates'], $statusCode);
    }
}
