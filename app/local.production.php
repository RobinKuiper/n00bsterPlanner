<?php

// Phpunit test environment

return function (array $settings): array {
    $settings['general']['dev_mode'] = false;

    // Authentication
    $settings['authentication']['secret'] = $_ENV['AUTH_SECRET'];

    // Error handler
    $settings['error']['display_error_details'] = false;

    // Websocket settings
    $settings['websocket']['host'] = $_ENV['WS_HOST'] ?? 'localhost';
    $settings['websocket']['port'] = $_ENV['WS_PORT'] ?? 8081;

    // Database settings
    $settings['doctrine']['dev_mode'] = false;
    $settings['doctrine']['connection']['host'] = $_ENV['MYSQL_HOST'] ?? 'host.docker.internal';
//    $settings['doctrine']['connection']['port'] = $_ENV['MYSQL_PORT'] ?? '3306';
    $settings['doctrine']['connection']['user'] = $_ENV['MYSQL_USER'] ?? 'root';
    $settings['doctrine']['connection']['password'] = $_ENV['MYSQL_PASSWORD'] ?? '';
    $settings['doctrine']['connection']['dbname'] = $_ENV['MYSQL_DATABASE'] ?? '';

    return $settings;
};
