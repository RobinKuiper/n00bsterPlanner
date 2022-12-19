<?php

namespace App\Application\Action\Frontend\Event;

use App\Application\Factory\ContainerFactory;
use App\Domain\Event\Service\EventReader;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

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

        $this->container->get(Twig::class)->render($response, 'event.html', ['event' => $event]);

        return $response;
    }
}
