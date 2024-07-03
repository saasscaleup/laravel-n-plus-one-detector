<?php

return [
    'enabled' => env('NPLUSONE_DETECTOR_ENABLED', true),
    'max_queries' => env('NPLUSONE_MAX_QUERIES', 100),
    'threshold' => env('NPLUSONE_THRESHOLD', 5),
    'query_lifetime' => env('NPLUSONE_QUERY_LIFETIME', 300),
];