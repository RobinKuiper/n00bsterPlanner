<?php

namespace App\Application\Action\Frontend\Event;

use App\Application\Factory\ContainerFactory;
use App\Domain\Event\Service\EventCreator;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class CreateAction
{
    /**
     * @var EventCreator
     */
    private EventCreator $eventCreator;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(EventCreator $eventCreator)
    {
        $this->eventCreator = $eventCreator;
        $this->container = ContainerFactory::createInstance();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $method = $request->getMethod();
        return $this->$method($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function GET(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->container->get(Twig::class)->render($response, 'event/create.html', [ 'EVENTS_ROUTE_GROUP' => EVENTS_ROUTE_GROUP ]);

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function POST(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $create = $this->eventCreator->createEvent($data);

        // Redirect to homepage if successful event
        if($create['success']){
            return redirect('/events/' . $create['event']->getIdentifier());
        }

        $this->container->get(Twig::class)->render($response, 'event/create.html', [
            'success' => $create['success'],
            'errors' => $create['errors'],
            'event' => $create['event'],
            'EVENTS_ROUTE_GROUP' => EVENTS_ROUTE_GROUP
        ]);

        return $response;
    }
}
