<?php

namespace App\Application\Middleware;

use App\Application\Support\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RedirectifAuthenticatedMiddleware
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        if(Auth::check()){
            return \redirect('/');
        }

        return $handler->handle($request);
    }
}
