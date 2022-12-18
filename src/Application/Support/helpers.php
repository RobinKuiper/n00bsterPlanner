<?php

use App\Application\Factory\ContainerFactory;
use App\Application\Support\Redirect;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

if (!function_exists('hash_password')) {
    /**
     * @param string $password
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function hash_password(string $password): string
    {
        $container = ContainerFactory::createInstance();
        $settings = $container->get('settings')['authentication'];

        return password_hash($password, PASSWORD_BCRYPT, [
            $settings['salt']
        ]);
    }
}

if (!function_exists('redirect')) {
    /**
     * @param string $to
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function redirect(string $to)
    {
        $container = ContainerFactory::createInstance();
        return $container->get(Redirect::class)('/');
    }
}
