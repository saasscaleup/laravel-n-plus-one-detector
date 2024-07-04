<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Saasscaleup\NPlusOneDetector\Models\NplusoneWarning;

class NPlusOneQueryListener
{
    protected $queries = [];
    protected $n_plus_one_queries_threshold;
    protected $n_plus_one_detector_threshold;

    public function __construct()
    {
        $this->n_plus_one_queries_threshold  = config('n-plus-one.queries_threshold');
        $this->n_plus_one_detector_threshold = config('n-plus-one.detector_threshold');
    }

    public function register()
    {
        
        DB::listen(function ($query) {
            if (stripos($query->sql, 'select') === 0) {
                try{
                    $location   = $this->getCallingLocation();
                    $md5        = md5($query->sql.$location);

                    if ( !Cache::has($md5)){
                        $this->queries[] = [
                            'sql' => $query->sql,
                            'bindings' => $query->bindings,
                            'time' => $query->time,
                            'location' => $location,
                        ];

                        // Detect N+1 queries
                        if (count($this->queries) >= $this->n_plus_one_queries_threshold) {
                            $this->detectNPlusOne();

                            // Clear queries to reset for the next batch
                            $this->queries = [];
                        }
                    }
                }catch(\Exception $e){
                    Log::error('laravel-n-plus-one-detector->register: ' .$e->getMessage());
                }
            }
        });
    }

    protected function detectNPlusOne()
    {
        $occurrences = [];

        try{
            foreach ($this->queries as $query) {
                $occurrences[md5($query['sql'].$query['location'])][] = $query;
            }

            foreach ($occurrences as $md5 => $queries) {

                if (count($queries) > $this->n_plus_one_detector_threshold && !Cache::has($md5)) {
                    $message = "Potential N+1 query detected: ".$queries[0]['sql'] . " executed " . count($queries) . " times at locations: " . $queries[0]['location'];
                    echo $message;
                    // Save warning to the database
                    NplusoneWarning::create([
                        'sql' => $queries[0]['sql'],
                        'location' => $queries[0]['location'],
                    ]);

                    Cache::put($md5, true, 120);
                }
            }

        }catch(\Exception $e){
            Log::error('laravel-n-plus-one-detector->detectNPlusOne: ' .$e->getMessage());
        }

    }

    protected function getCallingLocation()
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 50);

        foreach ($backtrace as $trace) {
            
            if (isset($trace['file']) && !str_contains($trace['file'], 'vendor/') && !str_contains($trace['file'], 'NPlusOneQueryListener.php')) {
                return $trace['file'] . ':' . $trace['line'];
            }
        }
        return 'Unknown location';
    }
}