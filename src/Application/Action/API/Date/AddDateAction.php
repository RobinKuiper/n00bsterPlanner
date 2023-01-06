<?php

namespace App\Application\Action\API\Date;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

final class AddDateAction extends DateAction
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

        $date = $this->dateService->createDate($data);

        if ($date['success']) {
            $this->emit('date_added', [
                'date' => $date['message']->getDate()->format('Y-m-d'),
                'room' => $date['identifier']
            ]);
        }

        return $this->respond($date);
    }
}
