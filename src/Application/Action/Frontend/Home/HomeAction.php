<?php

namespace App\Application\Action\Frontend\Home;

use App\Application\Factory\ContainerFactory;
use App\Application\Support\Auth;
use App\Domain\Event\Service\EventFinder;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class HomeAction
{
//    private EventFinder $eventFinder;
//
//    public function __construct(EventFinder $eventFinder) {
//        $this->eventFinder = $eventFinder;
//    }

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
        $container = ContainerFactory::createInstance();
        $user = Auth::user(); //$session->get('user');
        $events = Auth::check() ? $user->getAllEvents()->toArray() : [];//$this->eventFinder->findEvents();

        $container->get(Twig::class)->render($response, 'home.html', [ 'events' => $events, 'user' => $user ]);

        return $response;
    }
}
