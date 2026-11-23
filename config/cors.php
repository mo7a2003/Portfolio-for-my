<?php

$defaultOrigins = [
    'https://mohammed-alhwaiti-portfolio.vercel.app',
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://localhost:3000',
    'http://127.0.0.1:3000',
];

$allowedOriginsEnv = env('CORS_ALLOWED_ORIGINS');

if (! empty($allowedOriginsEnv)) {
    if (trim($allowedOriginsEnv) === '*') {
        $parsedOrigins = ['*'];
    } else {
        $parsedOrigins = array_values(array_filter(array_map('trim', explode(',', $allowedOriginsEnv))));
    }
} else {
    $parsedOrigins = $defaultOrigins;
}

$isWildcard = in_array('*', $parsedOrigins, true);

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Strictly configured to prevent unauthorized cross-origin requests
    | while permitting authenticated requests from trusted frontend deployments.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*', 'up'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => $parsedOrigins,

    'allowed_origins_patterns' => [
        // Allow Vercel preview branch deployments securely
        '#^https://mohammed-alhwaiti-portfolio.*\.vercel\.app$#',
        '#^https://.*-mo7a2003s-projects\.vercel\.app$#',
    ],

    'allowed_headers' => [
        'Content-Type',
        'X-Requested-With',
        'Authorization',
        'Accept',
        'Origin',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [
        'Authorization',
        'X-Total-Count',
        'Content-Disposition',
    ],

    'max_age' => 86400,

    // Credentials can only be true if NOT wildcard origin per W3C CORS spec
    'supports_credentials' => $isWildcard ? false : filter_var(env('CORS_SUPPORTS_CREDENTIALS', true), FILTER_VALIDATE_BOOLEAN),

];
