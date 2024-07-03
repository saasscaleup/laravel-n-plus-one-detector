<?php 

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Support\ServiceProvider;

class NPlusOneDetectorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/n-plus-one.php', 'n-plus-one');

        $this->app->singleton(NPlusOneQueryListener::class, function ($app) {
            return new NPlusOneQueryListener();
        });
    }

    public function boot()
    {
        $this->registerRoutes();
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'n-plus-one');

        $this->publishes([
            __DIR__ . '/config/n-plus-one.php' => config_path('n-plus-one.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/n-plus-one'),
        ], 'views');

        $this->registerNPlusOneListener();
    }

    protected function registerRoutes()
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes/web.php';
        }
    }

    protected function registerNPlusOneListener()
    {
        if (config('n-plus-one.enabled', true)) {
            $listener = $this->app->make(NPlusOneQueryListener::class);
            $listener->register();
        }
    }
}