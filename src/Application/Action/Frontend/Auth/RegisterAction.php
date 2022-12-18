<?php

namespace App\Application\Action\Frontend\Auth;

use App\Application\Factory\ContainerFactory;
use App\Domain\Auth\Service\RegisterService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class RegisterAction
{
    /**
     * @var RegisterService
     */
    private RegisterService $registerService;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param RegisterService $registerService
     * @throws Exception
     */
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
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
        $this->container->get(Twig::class)->render($response, 'register.html');

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function POST(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $register = $this->registerService->register($data);

        if($data['success']){
            return redirect('/');
        }

        $this->container->get(Twig::class)->render($response, 'register.html', [
            'success' => $register['success'],
            'errors' => $register['errors'],
            'user' => $register['user']
        ]);

        // Build the HTTP response
        return $response;
    }
}
