<?php

namespace App\Application\Middleware;

use App\Application\Factory\ContainerFactory;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Middleware implements MiddlewareInterface
{
    protected ResponseFactoryInterface $responseFactory;
    protected ContainerInterface $container;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @throws Exception
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->container = ContainerFactory::createInstance();
    }

    abstract public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler): ResponseInterface;
}
