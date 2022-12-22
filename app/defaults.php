<?php

// Application default settings

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Europe/Amsterdam');

$settings = [];

$settings['general'] = [
    'dev_mode' => true
];

// Authentication
$settings['authentication'] = [
    'secret' => 'SaltedString1234'
];

// Error handler
$settings['error'] = [
    // Should be set to false for the production environment
    'display_error_details' => true,
    // Should be set to false for the test environment
    'log_errors' => true,
    // Display error details in error log
    'log_error_details' => true,
];

// Logger settings
$settings['logger'] = [
    // Log file location
    'path' => __DIR__ . '/../logs',
    // Default log level
    'level' => \Monolog\Level::Info,
];

// Database settings
$settings['doctrine'] = [
    'dev_mode' => $settings['general']['dev_mode'],
    'cache_dir' => __DIR__. '/../cache',
    'metadata_dirs' => [__DIR__ . '/../src/Domain'],
    'connection' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'password' => 'root',
        'dbname'=> 'noobster',
        'charset' => 'utf8',
    ]
];

// Console commands
$settings['commands'] = [
    \App\Application\Console\ExampleCommand::class,
    \App\Application\Console\SetupCommand::class,
];

return $settings;
