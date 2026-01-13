<?php

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Load env if available
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

return [
    'paths' => [
        'migrations' => 'migrations',
        'seeds' => 'seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST') ?: '127.0.0.1',
            'name' => getenv('DB_DATABASE') ?: 'storage_db',
            'user' => getenv('DB_USERNAME') ?: 'root',
            'pass' => getenv('DB_PASSWORD') ?: '',
            'port' => getenv('DB_PORT') ?: 3306,
            'charset' => 'utf8mb4'
        ]
    ]
];
