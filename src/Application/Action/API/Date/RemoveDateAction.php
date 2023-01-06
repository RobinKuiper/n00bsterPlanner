<?php

namespace App\Application\Action\API\Date;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class RemoveDateAction extends DateAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        // TODO: Can't remove if date is picked

        $eventId = $this->args['eventId'];
        $date = $this->args['date'];
        $userId = $this->getAttribute('userId');

        $necessity = $this->dateService->removeDate($userId, $eventId, $date);

        if ($necessity['success']) {
            $this->emit('date_removed', [
                'date' => $necessity['date']->getDate()->format('Y-m-d'),
                'room' => $necessity['identifier']
            ]);
        }

        return $this->respond($necessity);
    }
}
