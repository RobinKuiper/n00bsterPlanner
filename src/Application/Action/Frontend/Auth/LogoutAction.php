<?php

namespace App\Application\Action\Frontend\Auth;

use App\Domain\Auth\Service\LogoutService;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LogoutAction
{
    /**
     * @var LogoutService
     */
    private LogoutService $logoutService;

    /**
     * @param LogoutService $logoutService
     * @throws Exception
     */
    public function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[NoReturn] public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->logoutService->logout();

        return redirect('/');
    }
}
