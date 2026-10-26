<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'power_bi' => [
        'embed_url' => env('POWER_BI_EMBED_URL'),
    ],

    'glpi' => [
        // GLPI 10 legacy REST API base, e.g. https://glpi.example.edu/apirest.php
        'enabled' => (bool) env('GLPI_ENABLED', false),
        'base_url' => rtrim((string) env('GLPI_BASE_URL', ''), '/'),
        'app_token' => env('GLPI_APP_TOKEN'),
        'user_token' => env('GLPI_USER_TOKEN'),
        'entity_id' => (int) env('GLPI_ENTITY_ID', 0),
        'default_group_id' => env('GLPI_DEFAULT_GROUP_ID'),
        'ticket_type' => (int) env('GLPI_TICKET_TYPE', 2),
        'categories' => [
            'technical_service' => env('GLPI_CATEGORY_TECHNICAL_SERVICE'),
            'asset_movement' => env('GLPI_CATEGORY_ASSET_MOVEMENT'),
            'maintenance_schedule' => env('GLPI_CATEGORY_MAINTENANCE_SCHEDULE'),
        ],
        // JSON map from normalized SIGMA email to GLPI user ID.
        'user_map' => json_decode((string) env('GLPI_USER_MAP', '{}'), true) ?: [],
        'connect_timeout' => (int) env('GLPI_CONNECT_TIMEOUT', 5),
        'timeout' => (int) env('GLPI_TIMEOUT', 20),
        'verify_tls' => (bool) env('GLPI_VERIFY_TLS', true),
    ],

];
