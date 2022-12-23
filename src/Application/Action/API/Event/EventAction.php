<?php

namespace App\Application\Action\API\Event;

use App\Application\Action\API\Action;
use App\Application\Factory\LoggerFactory;
use App\Domain\Event\Service\EventService;

abstract class EventAction extends Action
{
    protected EventService $eventService;

    public function __construct(EventService $eventService, LoggerFactory $loggerFactory)
    {
        parent::__construct($loggerFactory);
        $this->eventService = $eventService;
    }
}
