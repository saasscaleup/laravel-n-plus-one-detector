<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Saasscaleup\NPlusOneDetector\Models\NplusoneWarning;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Listener class for detecting N+1 queries in Laravel.
 *
 * This class listens for SELECT queries and detects when there are multiple
 * queries executed as a result of lazy loading relationships. It then logs
 * a warning and sends a notification.
 */
class NPlusOneQueryListener
{
    protected $queries = [];
    protected $n_plus_one_queries_threshold;
    protected $n_plus_one_detector_threshold;

    /**
     * Constructor for NPlusOneQueryListener
     *
     * Sets the N+1 queries threshold and the N+1 detector threshold.
     * The threshold values are retrieved from the configuration file.
     */
    public function __construct()
    {
        // Set the N+1 queries threshold
        $this->n_plus_one_queries_threshold  = config('n-plus-one.queries_threshold');
        
        // Set the N+1 detector threshold
        $this->n_plus_one_detector_threshold = config('n-plus-one.detector_threshold');
    }

    /**
     * Registers a listener for database queries and detects N+1 queries.
     *
     * The listener listens for SELECT queries and groups them by SQL and location.
     * If the number of queries in a group exceeds the threshold, a warning is logged
     * and a notification is sent. The queries are stored in an array and cleared
     * every time the threshold is reached. Duplicate warnings are prevented by caching
     * the warning with a unique MD5 hash of the SQL and location.
     *
     * @return void
     */
    public function register()
    {
        // Listen for database queries
        DB::listen(function ($query) {
            // Check if the query is a SELECT query
            if (stripos($query->sql, 'select') === 0 && str_contains($query->sql, 'comments')) {

                try{
                    // Get the calling location
                    $location   = $this->getCallingLocation();

                    // Generate a unique MD5 hash of the SQL and location
                    $md5        = md5($query->sql.$location);

                    // If the query has not been cached
                    if ( !Cache::has($md5)){
                        // Store the query in an array
                        $this->queries[] = [
                            'sql' => $query->sql,
                            'bindings' => $query->bindings,
                            'time' => $query->time,
                            'location' => $location,
                        ];

                        // If the number of queries in the array exceeds the threshold
                        if (count($this->queries) >= $this->n_plus_one_queries_threshold) {
                            // Detect N+1 queries and clear the queries array
                            $this->detectNPlusOne();
                            $this->queries = [];
                        }
                    }
                }catch(\Exception $e){
                    // Log any errors that occur
                    Log::error('laravel-n-plus-one-detector->register: ' .$e->getMessage());
                }
            }
        });
    }

    /**
     * Detects N+1 queries.
     *
     * This function groups the queries by their SQL and location, and then
     * checks if any of the groups exceed the threshold. If a group exceeds
     * the threshold, a warning is logged and a notification is sent.
     *
     * @return void
     */
    protected function detectNPlusOne()
    {
        $occurrences = [];

        try{
            // Group the queries by their SQL and location
            foreach ($this->queries as $query) {
                $occurrences[md5($query['sql'].$query['location'])][] = $query;
            }

            // Check each group for N+1 queries
            foreach ($occurrences as $md5 => $queries) {

                // If the group exceeds the threshold and is not cached
                if (count($queries) > $this->n_plus_one_detector_threshold && !Cache::has($md5)) {

                    // Generate the warning message
                    $message = "Potential N+1 query detected: ".$queries[0]['sql']
                        . " executed " . count($queries) . " times at locations: "
                        . $queries[0]['location'];
                    echo $message;

                    $classAndMethods = array_unique(array_map(function ($query) {
                        return explode(' in ', $query['location'])[0];
                    }, $queries));

                    dd($classAndMethods);
                    // Save warning to the database
                    NplusoneWarning::createRecord([
                        'sql' => $queries[0]['sql'],
                        'location' => $queries[0]['location'],
                    ]);

                    // Cache the warning to prevent duplicate warnings
                    Cache::put($md5, true, config('n-plus-one.cache_lifetime')*60);

                    // Send a notification
                    NotificationService::send($message);
                }
            }

        }catch(\Exception $e){
            // Log any errors that occur
            Log::error('laravel-n-plus-one-detector->detectNPlusOne: ' .$e->getMessage());
        }

    }

    /**
     * Get the location of the calling code.
     *
     * This function traverses the debug backtrace to find the first file
     * that is not part of the vendor directory and is not this file itself.
     *
     * @return string The location of the calling code, or 'Unknown location' if
     *                no such file is found.
     */
    protected function getCallingLocation()
    {
        // Get the debug backtrace with argument values ignored
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 50);

        // Iterate over the backtrace
        foreach ($backtrace as $trace) {
            // Check if the file is not part of the vendor directory and is not this file
            if (isset($trace['file']) && !str_contains($trace['file'], 'vendor/') && !str_contains($trace['file'], 'NPlusOneQueryListener.php')) {
                // Return the file and line number
                return $trace['file'] . ':' . $trace['line'];
            }
        }
        // Return 'Unknown location' if no suitable file is found
        return 'Unknown location';
    }

    // protected function getCallingLocation()
    // {
    //     $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 50);

    //     foreach ($backtrace as $trace) {
    //         if ($trace['function'] == "getRelationValue" || $trace['function'] ===Relation::class) {
    //             //echo '<pre>'. print_r($trace, true) .'</pre>';
    //             $relation = $trace;
    //             if (is_array($relation) && isset($relation['object'])) {
    //                 if ($relation['class'] === Relation::class) {
    //                     $model = get_class($relation['object']->getParent());
    //                     $relationName = get_class($relation['object']->getRelated());
    //                     $relatedModel = $relationName;
    //                 } else {
    //                     $model = get_class($relation['object']);
    //                     $relationName = $relation['args'][0];
    //                     $relatedModel = $relationName;
    //                 }


    //             }
    //         }
    //         if (isset($trace['file']) && !str_contains($trace['file'], 'vendor/') && !str_contains($trace['file'], 'NPlusOneQueryListener.php')) {
    //             $class = isset($trace['class']) ? $trace['class'] : 'Unknown class';
    //             $function = isset($trace['function']) ? $trace['function'] : 'Unknown function';
    //             return $class . '::' . $function . ' in ' . $trace['file'] . ':' . $trace['line'];
    //         }
    //     }
    //     return 'Unknown location';
    // }
}