<?php

namespace App\Application\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class IsGuestMiddleware extends Middleware
{
    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Check if there is an authorization header
        $authHeader = $request->getHeader('Authorization');
        if (!empty($authHeader)) {
            // An Authorization header was found
            // Send an error response and exit
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write("Trying to access an non authentication endpoint.");

            return $response
                ->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED)
                ->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
