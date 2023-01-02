<?php

namespace App\Application\Action\API\Date;

use App\Application\Action\API\Action;
use App\Application\Factory\LoggerFactory;
use App\Domain\Event\DateService;
use App\Domain\Necessity\NecessityService;

abstract class DateAction extends Action
{
    protected DateService $dateService;

    public function __construct(DateService $dateService, LoggerFactory $loggerFactory)
    {
        parent::__construct($loggerFactory);
        $this->dateService = $dateService;
    }
}
