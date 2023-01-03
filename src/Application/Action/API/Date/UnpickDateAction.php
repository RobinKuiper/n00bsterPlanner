<?php

namespace App\Application\Action\API\Date;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class UnpickDateAction extends DateAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['userId'] = $this->getAttribute('userId');

        $date = $this->dateService->unpickDate($data);

        return $this->respond($date);
    }
}
