<?php

// Define app routes

use App\Application\Action\API\Auth\LoginAction;
use App\Application\Action\API\Auth\LogoutAction;
use App\Application\Action\API\Auth\RegisterAction;
use App\Application\Action\API\Auth\RegisterGuestAction;
use App\Application\Action\API\Date\GetPickedDatesAction;
use App\Application\Action\API\Date\PickDateAction;
use App\Application\Action\API\Date\RemoveDateAction;
use App\Application\Action\API\Date\UnpickDateAction;
use App\Application\Action\API\Event\CreateEventAction;
use App\Application\Action\API\Event\GetAllEventsAction;
use App\Application\Action\API\Event\GetEventAction;
use App\Application\Action\API\Event\GetOwnedEventsAction;
use App\Application\Action\API\Event\JoinEventAction;
use App\Application\Action\API\Event\RemoveEventAction;
use App\Application\Action\API\Event\UpdateEventAction;
use App\Application\Action\API\Date\AddDateAction;
use App\Application\Action\API\Necessity\CreateNecessityAction;
use App\Application\Action\API\Necessity\RemoveNecessityAction;
use App\Application\Middleware\IsAuthenticatedMiddleware;
use App\Application\Middleware\IsGuestMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {

            $app->get('/test', LoginAction::class)->add(IsAuthenticatedMiddleware::class);

            /** AUTHENTICATION */
            $app->group('/authentication', function (RouteCollectorProxy $app) {
                $app->get('/logout', LogoutAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->post('/login', LoginAction::class)->add(IsGuestMiddleware::class);
                $app->post('/register', RegisterAction::class)->add(IsGuestMiddleware::class);
                $app->post('/register/guest', RegisterGuestAction::class)->add(IsGuestMiddleware::class);
            });

            /** EVENTS */
            $app->group('/events', function (RouteCollectorProxy $app) {
                $app->get('/all', GetAllEventsAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/owned', GetOwnedEventsAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/{identifier}', GetEventAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/join/{identifier}', JoinEventAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/remove/{event_id}', RemoveEventAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->post('/update', UpdateEventAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/create', CreateEventAction::class)->add(IsAuthenticatedMiddleware::class);
                // TODO: Check root route path
            });

            $app->group('/necessity', function (RouteCollectorProxy $app) {
                $app->post('/add', CreateNecessityAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/remove/{id}', RemoveNecessityAction::class)->add(IsAuthenticatedMiddleware::class);
            });

            $app->group('/date', function (RouteCollectorProxy $app) {
                $app->post('/add', AddDateAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/pick', PickDateAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/unpick', UnpickDateAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/remove/{id}', RemoveDateAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/get/{eventId}', GetPickedDatesAction::class)->add(IsAuthenticatedMiddleware::class);
            });
        }
    );
};
