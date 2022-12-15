<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

    $app->group('/events', function (RouteCollectorProxy $app) {
        $app->get('/{identifier}', \App\Action\Event\EventAction::class)->setName('event');
    });

    // API
    $app->group('/api',
        function (RouteCollectorProxy $app) {
            $app->group('/events',
                function (RouteCollectorProxy $app) {
                    $app->get('/', \App\Action\Event\EventFinderAction::class);
                    $app->get('/{event_id}', \App\Action\Event\EventReaderAction::class);

                    $app->post('/', \App\Action\Event\EventCreatorAction::class);
                }
            );

            $app->post('/user', \App\Action\User\UserUpdaterAction::class);

//            $app->group('/user',
//                function (RouteCollectorProxy $app) {
//
//                }
//            );
        }
    );
};
