<?php

namespace App\Action\Event;

use App\Domain\Event\Service\EventReader;
use App\Factory\ContainerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EventAction
{
    private EventReader $eventReader;
    private ContainerInterface $container;

    public function __construct(EventReader $eventReader) {
        $this->eventReader = $eventReader;
        $this->container = ContainerFactory::createInstance();
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $identifier = $args['identifier'];
        $event = $this->eventReader->getByIdentifier($identifier);

        // TODO: No Event -> Not found.

        $this->container->get('view')->render($response, 'event.html', ['event' => $event]);

        return $response;
    }
}
