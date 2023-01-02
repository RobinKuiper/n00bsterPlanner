<?php

namespace App\Application\Action\API\Date;

use App\Domain\Event\Models\Event;
use App\Domain\Necessity\Models\Necessity;
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
        // Extract the form data from the request body
        $id = $this->args['id'];
        $user = $this->getAttribute('user');

        // Invoke the Domain with inputs and retain the result
        $necessity = $this->dateService->removeDate($user, $id);

        // Get the appropriate status code
        $statusCode = $necessity['statusCode'] ?? ($necessity['success']
            ? StatusCodeInterface::STATUS_OK
            : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);

        // Build the HTTP response
        // Send the HTTP response
        return $this->respond($necessity, $statusCode);
    }
}
