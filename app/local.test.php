<?php

// Phpunit test environment

return function (array $settings): array {
    // Database settings
    $dbName = 'noobster_test';

    $settings['doctrine']['connection']['dbname'] = $dbName;

    $settings['db'] = [
        'driver' => \Cake\Database\Driver\Mysql::class,
        'host' => 'localhost',
        'database' => $dbName,
        'username' => 'root',
        'password' => 'root',
        'encoding' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        // Enable identifier quoting
        'quoteIdentifiers' => true,
        // Set to null to use MySQL servers timezone
        'timezone' => null,
        // Disable meta data cache
        'cacheMetadata' => false,
        // Disable query logging
        'log' => false,
        // PDO options
        'flags' => [
            // Turn off persistent connections
            PDO::ATTR_PERSISTENT => false,
            // Enable exceptions
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Emulate prepared statements
            PDO::ATTR_EMULATE_PREPARES => true,
            // Set default fetch mode to array
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Convert numeric values to strings when fetching.
            // Since PHP 8.1 integers and floats in result sets will be returned using native PHP types.
            // This option restores the previous behavior.
            PDO::ATTR_STRINGIFY_FETCHES => true,
        ],
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

    // Mocked Logger settings
    $settings['logger'] = [
        'test' => new \Monolog\Logger('test', [
            new \Monolog\Handler\TestHandler(),
            // new \Monolog\Handler\StreamHandler('php://output', \Monolog\Level::Warning),
        ]),
    ];

    return $settings;
};
