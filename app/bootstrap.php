<?php

use App\Application\Factory\ContainerFactory;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

const BASE_PATH = __DIR__ . '/..';

// Build DI Container Instance
$container = ContainerFactory::createInstance();

// Create App Instance
return $container->get(App::class);

