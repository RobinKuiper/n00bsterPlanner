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

    protected function getAttribute(string $name)
    {
        return $this->request->getAttribute($name);
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
     * @param array $data
     * @return Response
     */
    protected function respond(array $data): Response
    {
        $success = $data['success'] ?? false;
        $statusCode = $data['statusCode'] ?? null;
        $message = $data['message']
            ?? $data['errors']
            ?? $data['error']
            ?? (($success || $statusCode === 200 || $statusCode === 201)
                ? 'Ok' : 'Error');

        $statusCode = $statusCode ?? ($success
            ? StatusCodeInterface::STATUS_OK
            : StatusCodeInterface::STATUS_BAD_REQUEST);

        $json = json_encode($message, JSON_PRETTY_PRINT);
        if (!$json) {
            $json = json_last_error_msg();
        }
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
