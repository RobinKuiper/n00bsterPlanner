<?php

namespace App\Application\Action\API\Date;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class AddDateAction extends DateAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['userId'] = $this->getAttribute('userId');

        $date = $this->dateService->createDate($data);

        return $this->respond($date);
    }
}
