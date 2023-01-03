<?php

namespace App\Application\Action\API\Event;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class CreateEventAction extends EventAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['user'] = $this->getAttribute('user');

        $value = $this->eventService->createEvent($data);

        return $this->respond($value);
    }
}
