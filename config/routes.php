<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

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

            $app->get('/customers', \App\Action\Customer\CustomerFinderAction::class);
            $app->post('/customers', \App\Action\Customer\CustomerCreatorAction::class);
            $app->get('/customers/{customer_id}', \App\Action\Customer\CustomerReaderAction::class);
            $app->put('/customers/{customer_id}', \App\Action\Customer\CustomerUpdaterAction::class);
            $app->delete('/customers/{customer_id}', \App\Action\Customer\CustomerDeleterAction::class);
        }
    );
};
