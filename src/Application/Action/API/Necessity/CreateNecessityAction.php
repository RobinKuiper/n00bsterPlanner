<?php

namespace App\Application\Action\API\Necessity;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class CreateNecessityAction extends NecessityAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['userId'] = $this->getAttribute('userId');

        $value = $this->necessityService->createNecessity($data);

        if ($value['success']) {
            $this->emit('necessity_added', [
                'necessity' => $value['message'],
                'room'      => $value['identifier']
            ]);
        }

        return $this->respond($value);
    }
}
