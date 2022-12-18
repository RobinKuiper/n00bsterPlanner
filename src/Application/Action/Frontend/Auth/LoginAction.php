<?php

namespace App\Application\Action\Frontend\Auth;

use App\Application\Factory\ContainerFactory;
use App\Domain\Auth\Service\LoginService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class LoginAction
{
    /**
     * @var LoginService
     */
    private LoginService $loginService;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param LoginService $loginService
     * @throws Exception
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
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
        $this->container->get(Twig::class)->render($response, 'login.html', [ 'AUTH_ROUTE_GROUP' => AUTH_ROUTE_GROUP ]);

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
        $login = $this->loginService->login($data);

        // Redirect to homepage if successful login
        if($login['success']){
            return redirect('/');
        }

        $this->container->get(Twig::class)->render($response, 'login.html', [
            'success' => $login['success'],
            'errors' => $login['errors'],
            'user' => $login['user'],
            'AUTH_ROUTE_GROUP' => AUTH_ROUTE_GROUP
        ]);

        return $response;
    }
}
