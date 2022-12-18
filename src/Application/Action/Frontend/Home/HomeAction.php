<?php

namespace App\Application\Action\Frontend\Home;

use App\Application\Factory\ContainerFactory;
use App\Domain\Event\Service\EventFinder;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use SlimSession\Helper;

final class HomeAction
{
    private EventFinder $eventFinder;

    public function __construct(EventFinder $eventFinder) {
        $this->eventFinder = $eventFinder;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
//        $response->getBody()->write('Welcome World!');
        $container = ContainerFactory::createInstance();
        $events = $this->eventFinder->findEvents();

        $session = $container->get(Helper::class);
        $user = $session->get('user');

        $container->get(Twig::class)->render($response, 'home.html', [ 'events' => $events, 'user' => $user ]);

        return $response;
    }
}
