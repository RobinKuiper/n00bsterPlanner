<?php

namespace App\Application\Action\API\Event;

use Exception;
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
        $value['jwt'] = $this->getAttribute('jwt');

        return $this->respond($value);
    }
}
