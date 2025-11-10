<?php
// Configuration settings for the pharmacy dashboard application

return [
    'app_name' => 'Pharmacy Dashboard',
    'app_env' => 'development',
    'app_debug' => true,
    'app_url' => 'http://localhost/pharmacy-dashboard',

    'db' => [
        'host' => 'localhost',
        'database' => 'pharmacy_db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ],

    'session' => [
        'lifetime' => 120,
        'expire_on_close' => false,
    ],

    'csrf' => [
        'token_name' => 'csrf_token',
        'token_lifetime' => 3600,
    ],
];
?>