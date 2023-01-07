<?php

namespace App\Application\Action\API\Necessity;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;

final class UnpickNecessityAction extends NecessityAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $data = (array)$this->getFormData();
        $data['userId'] = $this->getAttribute('userId');

        $necessity = $this->necessityService->unpickNecessity($data);

        if ($necessity['success']) {
            $this->emit('necessity_unpicked', [
                'necessity' => $necessity['message'],
                'room' => $necessity['identifier']
            ]);
        }

        return $this->respond($necessity);
    }
}
