<?php

namespace App\Application\Middleware;

use App\Application\Factory\ContainerFactory;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class IsAuthenticated
{
    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Get the JWT token from the header
        $authHeader = $request->getHeader('Authorization');
        if (empty($authHeader)) {
            // No Authorization header was found
            // Send an error response and exit
//            $response->getBody()->write("FALSE!");

            return (new Response())
//                ->withHeader('Content-Type', 'application/json')
                ->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        // The Authorization header is present
        // Extract the JWT from the header
        $jwt = trim(substr($authHeader[0], 7));

        // Get secret key from the settings container
        $container = ContainerFactory::createInstance();
        $secret = $container->get('settings')['authentication']['secret'];

        try {
            $decoded = JWT::decode($jwt, new Key($secret, 'HS256'));
            // Valid
            return $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $response->getBody()->write($e->getMessage());
            // Invalid
            return $response->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED);
        }
    }
}
