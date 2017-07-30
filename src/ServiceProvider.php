<?php

namespace JustPlayinGames\Games\CannedKitty;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;


class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        /* views */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'justplayingames-game-canned-kitty');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/justplayingames-game-canned-kitty'),
        ], 'views');

        /* assets */
        $this->publishes([
            __DIR__.'/../public' => resource_path('public/assets/games/canned-kitty'),
        ], 'assets');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->router->group(
            ['namespace' => 'JustPlayinGames\Games\CannedKitty'], 
            function($router) {
                require (__DIR__ . '/../routes/web.php');
            }
        );
    }
}
