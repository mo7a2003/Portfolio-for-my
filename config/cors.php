<?php

$allowedOrigins = env('CORS_ALLOWED_ORIGINS');

if ($allowedOrigins === '*' || $allowedOrigins === null || $allowedOrigins === '') {
    $parsedOrigins = ['*'];
} else {
    $parsedOrigins = array_map('trim', explode(',', $allowedOrigins));
}

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*', 'up'],

    'allowed_methods' => ['*'],

    'allowed_origins' => $parsedOrigins,

    'allowed_origins_patterns' => [
        // Optional regex patterns for preview deployments (e.g. Vercel / Netlify branch deploys)
        // env('CORS_ALLOWED_ORIGIN_REGEX', '^https:\/\/.*\.vercel\.app$'),
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [
        'Authorization',
        'X-Total-Count',
        'Content-Disposition',
    ],

    'max_age' => 86400,

    'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', true),

];
