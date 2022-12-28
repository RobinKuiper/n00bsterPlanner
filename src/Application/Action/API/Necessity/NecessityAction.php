<?php

namespace App\Application\Action\API\Necessity;

use App\Application\Action\API\Action;
use App\Application\Factory\LoggerFactory;
use App\Domain\Necessity\NecessityService;

abstract class NecessityAction extends Action
{
    protected NecessityService $necessityService;

    public function __construct(NecessityService $necessityService, LoggerFactory $loggerFactory)
    {
        parent::__construct($loggerFactory);
        $this->necessityService = $necessityService;
    }
}
