<?php

use App\Application\Middleware\ValidationExceptionMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;

return function (App $app) {
    $setting = $app->getContainer()->get('settings');

    $app->addBodyParsingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
//    $app->add(ErrorMiddleware::class);
    $app->addErrorMiddleware(true, true, true);
};
