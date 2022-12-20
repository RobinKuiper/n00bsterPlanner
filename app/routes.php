<?php

// Define app routes

use App\Application\Action\API\Event\EventCreatorAction;
use App\Application\Action\API\Event\EventFinderAction;
use App\Application\Action\API\Event\EventReaderAction;
use App\Application\Action\Frontend\Auth\LoginAction;
use App\Application\Action\Frontend\Auth\RegisterAction;
use App\Application\Action\Frontend\Event\EventAction;
use App\Application\Action\Frontend\Home\HomeAction;
use App\Application\Action\User\UserUpdaterAction;
use App\Application\Middleware\RedirectifAuthenticatedMiddleware as RedirectIfAuthenticated;
use App\Application\Middleware\RedirectifGuestMiddleware as RedirectIfGuest;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', HomeAction::class)->setName('home');

    $app->group(AUTH_ROUTE_GROUP, function (RouteCollectorProxy $app) {
        $app->get('/register', RegisterAction::class)->setName('register')->add(RedirectIfAuthenticated::class);
        $app->post('/register', RegisterAction::class)->setName('register')->add(RedirectIfAuthenticated::class);

        $app->get('/login', LoginAction::class)->setName('login')->add(RedirectIfAuthenticated::class);
        $app->post('/login', LoginAction::class)->setName('login')->add(RedirectIfAuthenticated::class);

        $app->get('/logout', \App\Application\Action\Frontend\Auth\LogoutAction::class)->setName('logout')->add(RedirectIfGuest::class);
    });

    $app->group('/events', function (RouteCollectorProxy $app) {
        $app->get('/create', \App\Application\Action\Frontend\Event\CreateAction::class)->setName('create_event')->add(RedirectIfGuest::class);
        $app->post('/create', \App\Application\Action\Frontend\Event\CreateAction::class)->setName('create_event')->add(RedirectIfGuest::class);

        $app->get('/{identifier}', EventAction::class)->setName('event');
    });

    $app->post('/necessity/add', \App\Application\Action\Frontend\Necessity\AddAction::class)->setName('necessity_add')->add(RedirectIfGuest::class);

    // API
    $app->group('/api',
        function (RouteCollectorProxy $app) {
            $app->group('/events',
                function (RouteCollectorProxy $app) {
                    $app->get('/', EventFinderAction::class);
                    $app->get('/{event_id}', EventReaderAction::class);

                    $app->post('/', EventCreatorAction::class);
                }
            );

            $app->post('/user', UserUpdaterAction::class);

            $app->group('/user', function (RouteCollectorProxy $app) {
                $app->get('/register', EventAction::class);
                $app->get('/login', EventAction::class);
            });

//            $app->group('/user',
//                function (RouteCollectorProxy $app) {
//
//                }
//            );
        }
    );
};
