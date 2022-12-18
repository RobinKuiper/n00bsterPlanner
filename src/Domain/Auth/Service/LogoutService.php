<?php

namespace App\Domain\Auth\Service;

use App\Application\Factory\ContainerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SlimSession\Helper;

final class LogoutService
{
    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function logout(): void
    {
        // Set PhpSession
        $container = ContainerFactory::createInstance();
        $session = $container->get(Helper::class);
        $session->delete('user');
    }
}
