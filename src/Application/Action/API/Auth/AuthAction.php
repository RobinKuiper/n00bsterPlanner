<?php

namespace App\Application\Action\API\Auth;

use App\Application\Action\API\Action;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\Service\AuthenticationService;

abstract class AuthAction extends Action
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService, LoggerFactory $loggerFactory)
    {
        parent::__construct($loggerFactory);
        $this->authService = $authService;
    }
}
