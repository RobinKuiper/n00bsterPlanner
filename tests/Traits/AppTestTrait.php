<?php

namespace App\Test\Traits;

use App\Application\Factory\ContainerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selective\TestTrait\Traits\ArrayTestTrait;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Selective\TestTrait\Traits\MockTestTrait;
use Slim\App;

require_once __DIR__ . '/../../app/constants.php';

/**
 * App Test Trait.
 */
trait AppTestTrait
{
    use ArrayTestTrait;
    use ContainerTestTrait;
    use HttpTestTrait;
    use HttpJsonTestTrait;
    use LoggerTestTrait;
    use MockTestTrait;

    protected App $app;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        $container = ContainerFactory::createTestInstance();
        $this->app = $container->get(App::class);

        $this->setUpContainer($container);
        $this->setUpLogger();

        if (method_exists($this, 'setUpDatabase')) {
            $this->setUpDatabase(__DIR__ . '/../../resources/schema/schema.sql');
        }
    }

    protected function createJsonRequestWithHeaders(string $method, $uri, array $data = null, string $authorization = null): ServerRequestInterface
    {
        $request = $this->createRequest($method, $uri)->withAddedHeader('Authorization', $authorization);

        if ($data !== null) {
            $request->getBody()->write((string)json_encode($data));
        }

        return $request->withHeader('Content-Type', 'application/json');
    }
}
