<?php

namespace App\Application\Action\API\Event;

use App\Application\Renderer\JsonRenderer;
use App\Domain\Event\Service\EventCategory\EventCategoryCreator;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EventCategoryCreatorAction
{
    private JsonRenderer $renderer;

    private EventCategoryCreator $eventCategoryCreator;

    public function __construct(EventCategoryCreator $eventCategoryCreator, JsonRenderer $renderer)
    {
        $this->eventCategoryCreator = $eventCategoryCreator;
        $this->renderer = $renderer;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

//        $data = [ 'name' => 'Test' ];

        // Invoke the Domain with inputs and retain the result
        $eventCategory = $this->eventCategoryCreator->createEventCategory($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['event_category_id' => $eventCategory->getId()])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
