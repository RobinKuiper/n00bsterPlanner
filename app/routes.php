<?php

// Define app routes

use App\Application\Action\API\Auth\LoginAction;
use App\Application\Action\API\Auth\LogoutAction;
use App\Application\Action\API\Auth\RegisterAction;
use App\Application\Action\API\Event\EventCreateAction;
use App\Application\Action\API\Event\EventGetAllAction;
use App\Application\Action\API\Event\EventReaderAction;
use App\Application\Action\API\Sandbox\SandboxAction;
use App\Application\Middleware\IsAuthenticatedMiddleware;
use App\Application\Middleware\IsGuestMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // API
    $app->group('/api',
        function (RouteCollectorProxy $app) {

            $app->get('/test', LoginAction::class)->add(IsAuthenticatedMiddleware::class);

            /** AUTHENTICATION */
            $app->group('/authentication', function (RouteCollectorProxy $app) {
                $app->get('/logout', LogoutAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->post('/login', LoginAction::class)->add(IsGuestMiddleware::class);
                $app->post('/register', RegisterAction::class)->add(IsGuestMiddleware::class);
            });

            /** EVENTS */
            $app->group('/events', function (RouteCollectorProxy $app) {
                $app->get('/all', EventGetAllAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/{event_id}', EventReaderAction::class);

                $app->post('/create', EventCreateAction::class)->add(IsAuthenticatedMiddleware::class); // TODO: Check root route path
            });

            /** SANDBOX */
            $app->group('/sandbox', function (RouteCollectorProxy $app) {
               $app->post('/1', SandboxAction::class)->add(IsAuthenticatedMiddleware::class);
            });
        }
    );
};
