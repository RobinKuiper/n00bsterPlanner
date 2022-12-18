<?php

// Application default settings

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Europe/Amsterdam');

$settings = [];

// Authentication
$settings['authentication'] = [
    'salt' => 'SaltedString1234'
];

// Session
$settings['session'] = [
    'name' => 'n00bster',
    'lifetime' => '24 hour',
    'autorefresh' => true
//    'path' => null,
//    'domain' => null,
//    'secure' => false,
//    'httponly' => true,
//    'samesite' => 'Lax',
//    'handler',
//    'ini_settings' (https://www.php.net/manual/en/session.configuration.php)
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
    'dev_mode' => true,
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

$settings['twig'] = [
    'template_path' => BASE_PATH . '/src/Application/Views/templates',
    'cache_path' => BASE_PATH . '/cache'
];

// Console commands
$settings['commands'] = [
    \App\Application\Console\ExampleCommand::class,
    \App\Application\Console\SetupCommand::class,
];

return $settings;
