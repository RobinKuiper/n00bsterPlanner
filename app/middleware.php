<?php

use App\Application\Middleware\ValidationExceptionMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $setting = $app->getContainer()->get('settings');

    $app->addBodyParsingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
//    $app->add(ErrorMiddleware::class);
    $app->addErrorMiddleware(true, true, true);
    $app->add(new \Slim\Middleware\Session([
        'name' => $setting['session']['name'],
        'lifetime' => $setting['session']['lifetime'],
        'autorefresh' => $setting['session']['autorefresh']
    ]));
};
