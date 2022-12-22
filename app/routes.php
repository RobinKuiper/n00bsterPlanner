<?php

// Define app routes

use App\Application\Action\API\Auth\AuthAction;
use App\Application\Action\API\Auth\LoginAction;
use App\Application\Action\API\Auth\RegisterAction;
use App\Application\Action\API\Event\EventCreatorAction;
use App\Application\Action\API\Event\EventFinderAction;
use App\Application\Action\API\Event\EventReaderAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // API
    $app->group('/api',
        function (RouteCollectorProxy $app) {

            $app->get('/test', LoginAction::class)->add(\App\Application\Middleware\IsAuthenticated::class);

            /** AUTHENTICATION */
            $app->group('/authentication', function (RouteCollectorProxy $app) {
                $app->get('/logout', \App\Application\Action\API\Auth\LogoutAction::class);

                $app->post('/login', LoginAction::class);
                $app->post('/register', RegisterAction::class);
            });

            /** EVENTS */
            $app->group('/events',
                function (RouteCollectorProxy $app) {
                    $app->get('/', EventFinderAction::class);
                    $app->get('/{event_id}', EventReaderAction::class);

                    $app->post('/', EventCreatorAction::class);
                }
            );
        }
    );
};
