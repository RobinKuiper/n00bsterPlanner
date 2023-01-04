<?php

namespace App\Application\Middleware;

use App\Domain\Auth\AuthenticationService;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RegisterGuestMiddleware extends Middleware
{
    private AuthenticationService $authenticationService;
    protected ResponseFactoryInterface $responseFactory;

    public function __construct(AuthenticationService $authenticationService, ResponseFactoryInterface $responseFactory)
    {
        parent::__construct($responseFactory);
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Get the JWT token from the header
        $authHeader = $request->getHeader('Authorization');
        if (empty($authHeader)) {
            // No Authorization header was found
            // Register guest account
            $register = $this->authenticationService->registerGuest();

            $request = $request->withHeader('Authorization', 'Bearer ' . $register['message']);
            $request = $request->withAttribute('jwt', $register['message']);
        }

        return $handler->handle($request);
    }
}
