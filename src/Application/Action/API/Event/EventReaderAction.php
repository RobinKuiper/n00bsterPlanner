<?php

namespace App\Application\Action\API\Event;

use App\Application\Renderer\JsonRenderer;
use App\Domain\Event\Service\EventReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EventReaderAction
{
    /**
     * @var JsonRenderer
     */
    private JsonRenderer $renderer;

    /**
     * @var EventReader
     */
    private EventReader $eventReader;

    /**
     * @param EventReader $eventReader
     * @param JsonRenderer $renderer
     */
    public function __construct(EventReader $eventReader, JsonRenderer $renderer)
    {
        $this->eventReader = $eventReader;
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Fetch parameters from the request
        $eventId = (int)$args['event_id'];

        // Invoke the Domain with inputs and retain the result
        $event = $this->eventReader->getEvent($eventId);

        // Build the HTTP response
        return $this->renderer->json($response, $event);
    }
}
