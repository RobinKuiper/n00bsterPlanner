<?php

namespace App\Application\Action\API\User;

use App\Application\Renderer\JsonRenderer;
use App\Domain\Auth\Service\RegisterService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RegisterAction
{
    private JsonRenderer $renderer;

    private RegisterService $registerService;

    public function __construct(RegisterService $registerService, JsonRenderer $renderer)
    {
        $this->registerService = $registerService;
        $this->renderer = $renderer;
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $object = $this->registerService->register($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['user_id' => $object->getId()])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
