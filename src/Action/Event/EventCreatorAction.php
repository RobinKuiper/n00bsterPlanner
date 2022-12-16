<?php

namespace App\Action\Event;

use App\Domain\Event\Service\EventCreator;
use App\Renderer\JsonRenderer;
use DI\NotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws NotFoundException
     * @throws TransactionRequiredException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $event = $this->eventCreator->createEvent($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['event_id' => $event->getId()])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
