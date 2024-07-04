<?php

return [
    
    // Whether or not to enable the N+1 Detector
    'enabled' => env('NPLUSONE_ENABLED', true),
    
    // The maximum number of queries allowed before detecting n+1 queries
    'max_queries' => env('NPLUSONE_MAX_QUERIES', 100),
    
    // The number of queries below which no alert will be triggered
    'queries_threshold' => env('NPLUSONE_QUERIES_THRESHOLD', 50),
    
    // The number of queries below which no detector will be triggered
    'detector_threshold' => env('NPLUSONE_DETECTOR_THRESHOLD', 10),
    
    // The number of seconds a query will be stored in memory before being discarded
    'query_lifetime' => env('NPLUSONE_QUERY_LIFETIME', 300),
      
    // Slack webhook url for N + 1 Detector
    'slack_webhook_url' => env('NPLUSONE_SLACK_WEBHOOK_URL', ''),

    // notification email address for N + 1 Detector
    'notification_email' => env('NPLUSONE_NOTIFICATION_EMAIL', 'admin@example.com'), // also possible: 'admin@example.com,admin2@example.com'

    // notification email subject for N + 1 Detector
    'notification_email_subject' => env('NPLUSONE_NOTIFICATION_EMAIL_SUBJECT', 'N+1 Detector Notification'),
];