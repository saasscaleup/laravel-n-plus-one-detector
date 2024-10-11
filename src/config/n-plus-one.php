<?php

return [
    
    // Whether or not to enable the N+1 Detector
    'enabled' => env('NPLUSONE_ENABLED', true),
    
    // The number of queries below which no alert will be triggered
    'queries_threshold' => env('NPLUSONE_QUERIES_THRESHOLD', 50),
    
    // The number of queries below which no detector will be triggered
    'detector_threshold' => env('NPLUSONE_DETECTOR_THRESHOLD', 10),
    
    // The number in minutes a n+1 query will be stored in memory before being discarded. So it won't repeat itself
    'cache_lifetime' => env('NPLUSONE_CACHE_LIFETIME', 14400), // 10 days
      
    // Slack webhook url for N + 1 Detector
    'slack_webhook_url' => env('NPLUSONE_SLACK_WEBHOOK_URL', ''),

    // Custom webhook url for N + 1 Detector
    'custom_webhook_url' => env('NPLUSONE_CUSTOM_WEBHOOK_URL', ''),

    // Notification email address for N + 1 Detector
    'notification_email' => env('NPLUSONE_NOTIFICATION_EMAIL', 'admin@example.com'), // also possible: 'admin@example.com,admin2@example.com'

    // Notification email subject for N + 1 Detector
    'notification_email_subject' => env('NPLUSONE_NOTIFICATION_EMAIL_SUBJECT', 'N+1 Detector Notification'),

    // Dashboard Middleware for N + 1 Detector
    'dashboard_middleware' => env('NPLUSONE_DASHBOARD_MIDDLEWARE', ['web', 'auth']),

    // Dashboard Pagination for N + 1 Detector
    'dashboard_records_pagination' => env('NPLUSONE_DASHBOARD_RECORDS_PAGINATION', 10),

];
