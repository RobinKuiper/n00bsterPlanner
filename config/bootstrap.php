<?php

use App\Factory\ContainerFactory;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

// Build DI Container Instance
$container = ContainerFactory::createInstance();

// Create App Instance
return $container->get(App::class);

