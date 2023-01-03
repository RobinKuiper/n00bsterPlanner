<?php

namespace App\Application\Action\API\Event;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class JoinEventAction extends EventAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $identifier = $this->args['identifier'];
        $userId = $this->getAttribute('userId');

        $data = $this->eventService->joinEvent($identifier, $userId);

        return $this->respond($data);
    }
}
