<?php

namespace Rzb\SocialAuth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SocialAuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

         $this->registerRoutes();

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
        $this->mergeConfigFrom(__DIR__.'/../config/socialauth.php', 'socialauth');

        $this->app->singleton(SocialAuth::class, function ($app) {
            return new SocialAuth(
                request()->provider ?: config('socialauth.defaults.provider'),
                request()->sociable ?: config('socialauth.defaults.sociable'),
            );
        });

        $this->app->alias(SocialAuth::class, 'socialauth');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['socialauth'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__.'/../config/socialauth.php' => config_path('socialauth.php'),
        ], 'socialauth.config');
    }

    protected function registerRoutes(): void
    {
        Route::group($this->routesConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/socialauth.php');
        });
    }

    protected function routesConfiguration(): array
    {
        return [
            'prefix' => config('socialauth.routes.prefix'),
            'middleware' => config('socialauth.routes.middleware'),
            'controller' => config('socialauth.routes.controller'),
        ];
    }
}
