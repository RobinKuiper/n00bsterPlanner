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

        $id = $this->args['id'];
        $userId = $this->getAttribute('userId');

        $necessity = $this->dateService->removeDate($userId, $id);

        return $this->respond($necessity);
    }
}
