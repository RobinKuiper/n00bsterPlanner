<?php

namespace App\Application\Action\API\Event;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class UpdateEventAction extends EventAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['userId'] = $this->getAttribute('userId');

        $value = $this->eventService->updateEvent($data);

        return $this->respond($value);
    }
}
