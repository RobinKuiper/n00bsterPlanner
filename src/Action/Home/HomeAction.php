<?php

namespace App\Action\Home;

use App\Factory\ContainerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
//        $response->getBody()->write('Welcome World!');
        $container = ContainerFactory::createInstance();

        $container->get('view')->render($response, 'home.html');

        return $response;
    }
}
