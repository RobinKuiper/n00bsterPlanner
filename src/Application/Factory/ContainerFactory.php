<?php

namespace App\Application\Factory;

use DI\Container;
use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    /**
     * Create a new container instance.
     *
     * @return ContainerInterface The container
     * @throws Exception
     */
    public static function createInstance(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();

        // Set up settings
        $containerBuilder->addDefinitions(BASE_PATH . '/app/container.php');

        // Build PHP-DI Container instance
        return $containerBuilder->build();
    }
}
