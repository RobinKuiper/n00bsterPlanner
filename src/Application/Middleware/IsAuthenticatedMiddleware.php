<?php

namespace App\Application\Middleware;

use App\Domain\Auth\Models\User;
use Doctrine\ORM\EntityManager;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class IsAuthenticatedMiddleware extends Middleware
{
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
            // Send an error response and exit
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write("No Authorization header was found");

            return $response
                ->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED)
                ->withHeader('Content-Type', 'application/json');
        }

        // The Authorization header is present
        // Extract the JWT from the header
        $jwt = trim(substr($authHeader[0], 7));

        // Get secret key from the settings container
        $secret = $this->container->get('settings')['authentication']['secret'];

        try {
            $decoded = JWT::decode($jwt, new Key($secret, 'HS256'));
            // Valid

            $entityManager = $this->container->get(EntityManager::class);
            $user = $entityManager->getReference(User::class, $decoded->userId);
            $request = $request->withAttribute('user', $user);

            return $handler->handle($request);
        } catch (Exception $e) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write($e->getMessage());

            return $response
                ->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}
