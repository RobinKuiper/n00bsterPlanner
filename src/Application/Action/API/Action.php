<?php

namespace App\Application\Action\API;

use App\Application\Factory\LoggerFactory;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected LoggerInterface $logger;
    protected Request $request;
    protected Response $response;
    protected array $args;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory
            ->addFileHandler('actions.log')
            ->createLogger();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->response = $response;
        $this->request = $request;
        $this->args = $args;

        return $this->action();
    }

    /**
     * @return Response
     */
    abstract protected function action(): Response;

    /**
     * @return object|array|null
     */
    protected function getFormData(): object|array|null
    {
        return $this->request->getParsedBody();
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function resolveArg(string $name): mixed
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `$name`.");
        }

        return $this->args[$name];
    }

    /**
     * @param mixed|null $data
     * @param int $statusCode
     * @return Response
     */
    protected function respond(mixed $data = null, int $statusCode = StatusCodeInterface::STATUS_OK): Response
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
