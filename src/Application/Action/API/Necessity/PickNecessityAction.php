<?php

namespace App\Application\Action\API\Necessity;

use App\Domain\Event\Models\Event;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

final class PickNecessityAction extends NecessityAction
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

        $necessity = $this->necessityService->pickNecessity($data);

        if ($necessity['success']) {
            $this->emit('necessity_picked', [
                'necessity' => $necessity['message'],
                'room' => $necessity['identifier']
            ]);
        }

        return $this->respond($necessity);
    }
}
