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

        if ($date['success']) {
            $obj = [
                'date' => $date['message']->getDate()->format("Y-m-d"),
                'user' => $date['user']
            ];
            $this->emit('date_unpicked', [ 'object' => $obj, 'room' => $date['identifier'] ]);
        }

        return $this->respond($date);
    }
}
