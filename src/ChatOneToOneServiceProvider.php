<?php

namespace SalvatoreCervone\ChatOneToOne;

use Illuminate\Support\ServiceProvider;

class ChatOneToOneServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'salvatorecervone');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'salvatorecervone');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        // $this->publishesMigrations([
        //     __DIR__ . '/../database/migrations' => database_path('migrations'),
        // ]);
        $this->loadViewsFrom(__DIR__ . '/resources/js/Pages', 'chatonetoone');


        $this->publishes([
            __DIR__ . '/resources/js/Pages' => resource_path('js/Pages/chatonetoone/')
        ], 'vue-components');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/chatonetoone.php', 'chatonetoone');

        // Register the service the package provides.
        $this->app->singleton('chatonetoone', function ($app) {
            return new ChatOneToOne;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['chatonetoone'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/chatonetoone.php' => config_path('chatonetoone.php'),
        ], 'chatonetoone.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/salvatorecervone'),
        ], 'chatonetoone.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/salvatorecervone'),
        ], 'chatonetoone.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/salvatorecervone'),
        ], 'chatonetoone.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
