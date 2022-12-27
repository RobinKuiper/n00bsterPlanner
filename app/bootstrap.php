<?php

use App\Application\Factory\ContainerFactory;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/constants.php';

// Build DI Container Instance
$container = ContainerFactory::createInstance();

// Create App Instance
return $container->get(App::class);

