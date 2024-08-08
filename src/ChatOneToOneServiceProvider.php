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

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->publishes([__DIR__ . '/resources/js' => resource_path('js/')], 'js-chatonetoone');
        $this->publishes([__DIR__ . '/resources/js/Pages' => resource_path('js/Pages/chatonetoone/')], 'vue-chatonetoone');
        $this->publishes([__DIR__ . '/Http/Controllers' => app_path('Http/Controllers/')], 'controller-chatonetoone');
        $this->publishes([__DIR__ . '/Models' => app_path('Models/')], 'model-chatonetoone');

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

     
    }
}
