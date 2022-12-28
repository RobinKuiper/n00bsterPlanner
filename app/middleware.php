<?php

use App\Application\Middleware\ValidationExceptionMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Exception\HttpNotFoundException;

return function (App $app) {
    $setting = $app->getContainer()->get('settings');

    $app->addBodyParsingMiddleware();
    $app->add(\App\Application\Middleware\CorsMiddleware::class);
    $app->add(ValidationExceptionMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
//    $app->add(ErrorMiddleware::class);
    $app->addErrorMiddleware(true, true, true);

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

//    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
//        throw new HttpNotFoundException($request);
//    });
};
