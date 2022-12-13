<?php

namespace App\Action\Event;

use App\Domain\Event\Service\EventCreator;
use App\Renderer\JsonRenderer;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EventCreatorAction
{
    private JsonRenderer $renderer;

    private EventCreator $eventCreator;

    public function __construct(EventCreator $eventCreator, JsonRenderer $renderer)
    {
        $this->eventCreator = $eventCreator;
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

//        $data = [
//            'title' => 'Test Title',
//            'description' => 'Test Description',
//            'category' => 'test'
//        ];

        // Invoke the Domain with inputs and retain the result
        $eventCategory = $this->eventCreator->createEvent($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['event_category_id' => $eventCategory->getId()])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
