<?php

namespace App\Application\Action\API\Necessity;

use Exception;
use Psr\Http\Message\ResponseInterface;

final class RemoveNecessityAction extends NecessityAction
{
    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function action(): ResponseInterface
    {
        $id = $this->args['id'];
        $userId = $this->getAttribute('userId');

        $data = $this->necessityService->removeNecessity($userId, $id);

        if ($data['success']) {
            $this->emit('necessity_removed', [
                'necessity' => $data['necessity'],
                'room'      => $data['identifier']
            ]);
        }

        return $this->respond($data);
    }
}
