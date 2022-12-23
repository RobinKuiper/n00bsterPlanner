<?php

namespace App\Application\Action\Frontend\Necessity;

use App\Application\Factory\ContainerFactory;
use App\Domain\Event\Service\EventService;
use App\Domain\Event\Service\NecessityAdder;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class AddAction
{
    /**
     * @var NecessityAdder
     */
    private NecessityAdder $necessityAdder;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(NecessityAdder $necessityAdder)
    {
        $this->necessityAdder = $necessityAdder;
        $this->container = ContainerFactory::createInstance();
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $add = $this->necessityAdder->add($data);

        // Redirect to homepage if successful event
        if($add['success']){
            return redirect('/events/' . $add['event']->getIdentifier());
        }

        $this->container->get(Twig::class)->render($response, 'event/create.html', [
            'success' => $add['success'],
            'errors' => $add['errors'],
            'event' => $add['event'],
        ]);

        return $response;
    }
}
