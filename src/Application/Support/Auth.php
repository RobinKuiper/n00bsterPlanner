<?php

namespace App\Application\Support;

use App\Application\Factory\ContainerFactory;
use App\Domain\Auth\Models\User;
use App\Domain\Auth\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
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
    public static function user(): User|null
    {
        $container = ContainerFactory::createInstance();
        $session = $container->get(Helper::class);

        $user = $session->get('user');

        if(!$user) return null;

        return $container->get(EntityManager::class)->find(User::class, $user->getId());
    }

    /**
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function check(): bool
    {
        return self::user() !== null;
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
