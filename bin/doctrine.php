<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../app/bootstrap.php';

$entityManager = $app->getContainer()->get(EntityManager::class);

$commands = [];

ConsoleRunner::run(new SingleManagerProvider($entityManager), $commands);
