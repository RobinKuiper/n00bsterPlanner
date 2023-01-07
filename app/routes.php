<?php

// Define app routes

use App\Application\Action\API\Auth\LoginAction;
use App\Application\Action\API\Auth\LogoutAction;
use App\Application\Action\API\Auth\RegisterAction;
use App\Application\Action\API\Auth\RegisterGuestAction;
use App\Application\Action\API\Date\GetAllPickedDatesAction;
use App\Application\Action\API\Date\GetUsersPickedDatesAction;
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
use App\Application\Action\API\Necessity\PickNecessityAction;
use App\Application\Action\API\Necessity\RemoveNecessityAction;
use App\Application\Action\API\Necessity\UnpickNecessityAction;
use App\Application\Middleware\IsAuthenticatedMiddleware;
use App\Application\Middleware\IsGuestMiddleware;
use App\Application\Middleware\RegisterGuestMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            /** AUTHENTICATION */
            $app->group('/authentication', function (RouteCollectorProxy $app) {
                $app->get('/logout', LogoutAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->post('/login', LoginAction::class)->add(IsGuestMiddleware::class);
                $app->post('/register', RegisterAction::class)->add(IsGuestMiddleware::class);
                $app->post('/register/guest', RegisterGuestAction::class)->add(IsGuestMiddleware::class);
            });

            /** EVENTS */
            $app->group('/events', function (RouteCollectorProxy $app) {
                $app->get('', GetAllEventsAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/owned', GetOwnedEventsAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/{identifier}', GetEventAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/join/{identifier}', JoinEventAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->put('', UpdateEventAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('', CreateEventAction::class)
                    ->add(IsAuthenticatedMiddleware::class)->add(RegisterGuestMiddleware::class);

                $app->delete('/{event_id}', RemoveEventAction::class)->add(IsAuthenticatedMiddleware::class);
                // TODO: Check root route path
            });

            /** NECESSITIES */
            $app->group('/necessity', function (RouteCollectorProxy $app) {
                $app->post('', CreateNecessityAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/pick', PickNecessityAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/unpick', UnpickNecessityAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->delete('/{id}', RemoveNecessityAction::class)->add(IsAuthenticatedMiddleware::class);
            });

            /** DATES */
            $app->group('/date', function (RouteCollectorProxy $app) {
                $app->get('/user/{eventId}', GetUsersPickedDatesAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->get('/all/{eventId}', getAllPickedDatesAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->post('', AddDateAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/pick', PickDateAction::class)->add(IsAuthenticatedMiddleware::class);
                $app->post('/unpick', UnpickDateAction::class)->add(IsAuthenticatedMiddleware::class);

                $app->delete('/{eventId}/{date}', RemoveDateAction::class)->add(IsAuthenticatedMiddleware::class);
            });
        }
    );
};
