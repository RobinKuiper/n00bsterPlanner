<?php

namespace App\Application\Action\API\Auth;

use App\Application\Action\API\Action;
use App\Application\Factory\LoggerFactory;
use App\Domain\Auth\AuthenticationService;

abstract class AuthAction extends Action
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $eventService, LoggerFactory $loggerFactory)
    {
        parent::__construct($loggerFactory);
        $this->authService = $eventService;
    }
}
