<?php

use Slim\App;
use DI\Container;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use UnexpectedValueException;

trait AppTestTrait
{
    protected $container;
    protected App $app;

    protected function setUp(): void
    {
        $this->app = require __DIR__ . '../app/bootstrap.php';

        $container = $this->app->getContainer();
        if($container === null) {
            throw new \http\Exception\UnexpectedValueException('Container must be initialized.');
        }

        $this->container = $container;
    }

    protected function mock(string $class): \PHPUnit\Framework\MockObject\MockObject
    {
        if (!class_exists($class)) {
            throw new \http\Exception\InvalidArgumentException(sprintf('Class not found: $s', $class));
        }

        $mock = $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->set($class, $mock);

        return $mock;
    }

    protected function createRequest(
        string $method,
        string $uri,
        array $serverParams = []
    ): \Psr\Http\Message\ServerRequestInterface {
        return (new \Slim\Factory\ServerRequestCreatorFactory())->createServerRequest($method, $uri, $serverParams);
    }
}
