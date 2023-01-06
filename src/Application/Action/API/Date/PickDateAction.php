<?php

namespace App\Application\Action\API\Date;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

final class PickDateAction extends DateAction
{
    /**
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['userId'] = $this->getAttribute('userId');

        $date = $this->dateService->pickDate($data);

        if ($date['success']) {
            $obj = [
                'date' => $date['message']->getDate()->format("Y-m-d"),
                'user' => $date['user']
            ];
            $this->emit('date_picked', [ 'object' => $obj, 'room' => $date['identifier'] ]);
        }

        return $this->respond($date);
    }
}
