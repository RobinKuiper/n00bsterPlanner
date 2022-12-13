<?php

namespace App\Action\Home;

use App\Domain\Event\Service\EventFinder;
use App\Factory\ContainerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeAction
{
    private EventFinder $eventFinder;

    public function __construct(EventFinder $eventFinder) {
        $this->eventFinder = $eventFinder;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
//        $response->getBody()->write('Welcome World!');
        $container = ContainerFactory::createInstance();
        $events = $this->eventFinder->findEvents();

        $container->get('view')->render($response, 'home.html', [ 'events' => $events ]);

        return $response;
    }
}
