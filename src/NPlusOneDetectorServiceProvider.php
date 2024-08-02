<?php 

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Support\ServiceProvider;


/**
 * Service provider for Laravel N+1 Detector.
 */
class NPlusOneDetectorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge the configuration from the package.
        $this->mergeConfigFrom(__DIR__ . '/config/n-plus-one.php', 'n-plus-one');

        // Register the NPlusOneQueryListener as a singleton.
        $this->app->singleton(NPlusOneQueryListener::class, function ($app) {
            return new NPlusOneQueryListener();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the routes.
        $this->registerRoutes();

        // Load the views.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'n-plus-one');

        if ($this->app->runningInConsole()) {

            // Publish the configuration file.
            $this->publishes([
                __DIR__ . '/config/n-plus-one.php' => config_path('n-plus-one.php'),
            ], 'config');

            // Publish the views.
            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/vendor/n-plus-one'),
            ], 'views');

            // Publish the migration.
            if (!class_exists('CreateNplusoneWarningsTable')) {
                $this->publishes([
                    __DIR__ . '/migrations/2024_07_03_000000_create_nplusone_warnings_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_nplusone_warnings_table.php'),
                ], 'migrations');
            }
        }
        
        // Register the NPlusOneQueryListener if enabled.
        $this->registerNPlusOneListener();
    }

    /**
     * Register the routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        // Register the routes if caching is not enabled.
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes/web.php';
        }
    }

    /**
     * Register the NPlusOneQueryListener if enabled.
     *
     * @return void
     */
    protected function registerNPlusOneListener()
    {
        // Register the NPlusOneQueryListener if enabled in the configuration.
        if (config('n-plus-one.enabled', true)) {
            $listener = $this->app->make(NPlusOneQueryListener::class);
            $listener->register();
        }
    }
}