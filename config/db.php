<?php
$env_file = '.env';
$env_dir = dirname(__DIR__);
if (getenv('APP_ENV') == 'testing') {
    $env_file .= '.testing';
}
$dotenv = new \Dotenv\Dotenv($env_dir, $env_file);
$dotenv->load();

$value = [
    'driver' => getenv('DB_DRIVER'),
    'host' => getenv('DB_HOST'),
    'port' => getenv('DB_PORT'),
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_PASSWORD'),
    'name' => getenv('DB_NAME')
];
