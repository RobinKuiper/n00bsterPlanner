<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', \App\Application\Action\Frontend\Home\HomeAction::class)->setName('home');

    $app->group('/events', function (RouteCollectorProxy $app) {
        $app->get('/{identifier}', \App\Application\Action\API\Event\EventAction::class)->setName('event');
    });

    // API
    $app->group('/api',
        function (RouteCollectorProxy $app) {
            $app->group('/events',
                function (RouteCollectorProxy $app) {
                    $app->get('/', \App\Application\Action\API\Event\EventFinderAction::class);
                    $app->get('/{event_id}', \App\Application\Action\API\Event\EventReaderAction::class);

                    $app->post('/', \App\Application\Action\API\Event\EventCreatorAction::class);
                }
            );

            $app->post('/user', \App\Application\Action\User\UserUpdaterAction::class);

//            $app->group('/user',
//                function (RouteCollectorProxy $app) {
//
//                }
//            );
        }
    );
};
