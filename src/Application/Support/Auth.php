<?php

namespace App\Application\Support;

use App\Application\Factory\ContainerFactory;
use App\Domain\Auth\Models\User;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SlimSession\Helper;

class Auth
{
    /**
     * @return User|false
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function user(): User|false
    {
        $container = ContainerFactory::createInstance();
        $session = $container->get(Helper::class);

        $user = $session->get('user');

        if(!$user) return false;

        return $user;
    }

    /**
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function check(): bool
    {
        return self::user() !== false;
    }

    /**
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function guest(): bool
    {
        return self::user() === false;
    }
}
