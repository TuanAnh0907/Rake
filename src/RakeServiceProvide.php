<?php

namespace TuanAnh0907\Rake;

use Illuminate\Support\ServiceProvider;

class RakeServiceProvide extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('Rake.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'rake');

        // Register the main class to use with the facade
        $this->app->singleton('rake', function () {
            return new Rake;
        });
    }

}