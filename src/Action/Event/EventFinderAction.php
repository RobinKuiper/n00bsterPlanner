<?php

namespace App\Action\Event;

use App\Domain\Event\Service\EventFinder;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EventFinderAction
{
    /**
     * @var JsonRenderer
     */
    private JsonRenderer $renderer;

    /**
     * @var EventFinder
     */
    private EventFinder $eventFinder;

    /**
     * @param EventFinder $eventFinder
     * @param JsonRenderer $renderer
     */
    public function __construct(EventFinder $eventFinder, JsonRenderer $renderer)
    {
        $this->eventFinder = $eventFinder;
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Invoke the Domain with inputs and retain the result
        $events = $this->eventFinder->findEvents();

        // Build the HTTP response
        return $this->renderer->json($response, $events);
    }
}
