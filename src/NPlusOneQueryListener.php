<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NPlusOneQueryListener
{
    protected $queries = [];
    protected $nPlusOneThreshold;

    public function __construct()
    {
        $this->nPlusOneThreshold = config('n-plus-one.threshold');
    }

    public function register()
    {
        DB::listen(function ($query) {
            
            $location = $this->getCallingLocation();

            $this->queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
                'location' => $location,
            ];

            // Detect N+1 queries
            if (count($this->queries) >= $this->nPlusOneThreshold) {
                $this->detectNPlusOne();
            }
        });
    }

    protected function detectNPlusOne()
    {
        $occurrences = [];

        foreach ($this->queries as $query) {
            $occurrences[md5($query['sql'].$query['location'])][] = $query;
        }

        foreach ($occurrences as $md5 => $queries) {
            
            if (count($queries) > 10 && !Cache::has($md5)) {
                $message = "Potential N+1 query detected: ".$queries[0]['sql'] . " executed " . count($queries) . " times at locations: " . $queries[0]['location'];
                var_dump($message);
                Cache::put($md5, true, 120);
            }
        }

        // Clear queries to reset for the next batch
        $this->queries = [];
    }

    protected function getCallingLocation()
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 50);
        foreach ($backtrace as $trace) {
            // please fix this
            
            if (isset($trace['file']) && !str_contains($trace['file'], 'vendor/') && !str_contains($trace['file'], 'NPlusOneQueryListener.php')) {
                return $trace['file'] . ':' . $trace['line'];
            }
        }
        return 'Unknown location';
    }
}